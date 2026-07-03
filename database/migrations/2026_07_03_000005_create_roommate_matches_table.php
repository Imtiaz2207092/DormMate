<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roommate_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_one_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_two_id')->constrained('users')->cascadeOnDelete();
            $table->integer('compatibility_score')->default(0);
            $table->timestamp('matched_at')->useCurrent();
            $table->enum('status', ['active', 'ended'])->default('active');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            $table->unique(['student_one_id', 'student_two_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roommate_matches');
    }
};
