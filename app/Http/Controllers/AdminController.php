<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\RoommateRequest;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $totalUsers = User::count();
        $totalProfiles = StudentProfile::count();
        $activeUsers = User::where('active', true)->count();
        $pendingRequests = RoommateRequest::where('status', 'pending')->count();
        $totalMessages = Message::count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalProfiles' => $totalProfiles,
            'activeUsers' => $activeUsers,
            'pendingRequests' => $pendingRequests,
            'totalMessages' => $totalMessages,
        ]);
    }

    public function users(Request $request)
    {
        $q = $request->input('q');
        $query = User::latest();

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users', [
            'users' => $users,
            'q' => $q,
        ]);
    }

    public function show($id)
    {
        $user = User::with(['studentProfile', 'studentPreference'])->findOrFail($id);
        return view('admin.show', ['user' => $user]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'user_type' => ['required', 'string', 'in:student,admin'],
            'active' => ['required', 'in:0,1'],
        ]);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'user_type' => $request->input('user_type'),
            'active' => (bool)$request->input('active'),
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete the currently logged in admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted successfully.');
    }

    public function statistics()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('user_type', 'admin')->count();
        $totalStudents = User::where('user_type', 'student')->count();
        $profilesCompleted = StudentProfile::count();
        $recentUsers = User::latest()->take(10)->get();

        return view('admin.statistics', [
            'totalUsers' => $totalUsers,
            'totalAdmins' => $totalAdmins,
            'totalStudents' => $totalStudents,
            'profilesCompleted' => $profilesCompleted,
            'recentUsers' => $recentUsers,
        ]);
    }

    public function reports()
    {
        return view('admin.placeholder', [
            'title' => 'Reports',
            'icon' => 'bi-file-earmark-text',
        ]);
    }

    public function settings()
    {
        return view('admin.placeholder', [
            'title' => 'Settings',
            'icon' => 'bi-gear',
        ]);
    }
}
