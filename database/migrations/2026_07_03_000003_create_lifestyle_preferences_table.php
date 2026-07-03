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
        Schema::create('student_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');

            $table->enum('sleep_schedule', ['Early Sleeper', 'Late Sleeper', 'Flexible']);
            $table->time('wake_up_time');
            $table->enum('study_habit', ['Silent', 'Group Study', 'Flexible']);
            $table->enum('cleanliness', ['Low', 'Medium', 'High']);
            $table->boolean('smoking');
            $table->enum('noise_tolerance', ['Low', 'Medium', 'High']);
            $table->enum('guests_frequency', ['Never', 'Sometimes', 'Frequently']);
            $table->enum('room_temperature', ['Cold', 'Moderate', 'Warm']);
            $table->enum('music_preference', ['Quiet', 'Soft Music', 'Loud Music']);
            $table->enum('lights_preference', ['Dark', 'Dim', 'Bright']);
            $table->enum('introvert_extrovert', ['Introvert', 'Ambivert', 'Extrovert']);
            $table->boolean('sleep_with_light');
            $table->boolean('pets');
            $table->text('hobbies');
            $table->text('languages');
            $table->text('additional_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_preferences');
    }
};
