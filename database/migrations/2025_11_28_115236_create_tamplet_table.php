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
        if (!Schema::hasTable('tamplet')) {
            Schema::create('tamplet', function (Blueprint $table) {
                $table->id();
                $table->integer('free_paid')->default(0); // 1 for paid, 0 for free
                $table->integer('type'); // 1 for video, 2 for gif
                $table->date('event_date')->nullable();
                $table->unsignedBigInteger('sub_category_id');
                $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
                $table->text('path')->nullable(); 
                $table->string('font_type')->nullable();
                $table->string('font_size')->nullable();
                $table->string('font_color')->nullable();
                $table->string('lable')->nullable();
                $table->string('lablebg')->nullable();
                $table->string('language')->nullable();
                $table->string('planImgName')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamplet');
    }
};
