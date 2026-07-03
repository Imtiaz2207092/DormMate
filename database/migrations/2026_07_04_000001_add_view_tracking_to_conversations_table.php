<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->unsignedBigInteger('last_opened_by')->nullable()->after('user_two_id');
            $table->timestamp('last_opened_at')->nullable()->after('last_opened_by');
        });
    }

    public function down()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn(['last_opened_by', 'last_opened_at']);
        });
    }
};
