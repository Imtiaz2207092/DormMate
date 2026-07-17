<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoommateMatch;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $totalMatches = RoommateMatch::count();
        $totalHalls = StudentProfile::whereNotNull('hall')->where('hall', '!=', '')->distinct()->count('hall');
        $totalDepartments = StudentProfile::whereNotNull('department')->where('department', '!=', '')->distinct()->count('department');

        $topDepartments = StudentProfile::whereNotNull('department')
            ->where('department', '!=', '')
            ->select('department', DB::raw('count(*) as total'))
            ->groupBy('department')
            ->orderByDesc('total')
            ->get();

        return view('welcome', compact('totalUsers', 'totalMatches', 'totalHalls', 'totalDepartments', 'topDepartments'));
    }
}
