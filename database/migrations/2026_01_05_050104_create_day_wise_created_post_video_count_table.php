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
        if (!Schema::hasTable('day_wise_created_post_video_count')) {
            Schema::create('day_wise_created_post_video_count', function (Blueprint $table) {
                $table->id();
                $table->date('daily_date')->nullable();
                $table->integer('post_count')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_wise_created_post_video_count');
    }
};
