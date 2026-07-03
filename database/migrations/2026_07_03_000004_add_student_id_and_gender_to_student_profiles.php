<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('student_profiles', 'student_id')) {
            Schema::table('student_profiles', function (Blueprint $table) {
                $table->string('student_id')->unique()->after('user_id');
            });
        }

        if (! Schema::hasColumn('student_profiles', 'gender')) {
            Schema::table('student_profiles', function (Blueprint $table) {
                $table->string('gender')->nullable()->after('student_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn(['student_id', 'gender']);
        });
    }
};
