<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roommate_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('roommate_requests', 'accepted_at')) {
                $table->timestamp('accepted_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('roommate_requests', function (Blueprint $table) {
            if (Schema::hasColumn('roommate_requests', 'accepted_at')) {
                $table->dropColumn('accepted_at');
            }
        });
    }
};
