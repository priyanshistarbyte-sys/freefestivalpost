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
         if (!Schema::hasTable('notification')) {
            Schema::create('notification', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->string('oprating_system');
                $table->integer('app_version');
                $table->text('token');
                $table->integer('device_id');
                $table->timestamps();
            });
         }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};
