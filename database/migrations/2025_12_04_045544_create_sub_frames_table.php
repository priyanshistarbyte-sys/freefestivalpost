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
        if (!Schema::hasTable('sub_frames')) {
            Schema::create('sub_frames', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('frame_id');
                $table->foreign('frame_id')->references('id')->on('frames')->onDelete('cascade');
                $table->text('image');
                $table->integer('status')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_frames');
    }
};
