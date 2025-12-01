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
        if (!Schema::hasTable('videogif')) {
            Schema::create('videogif', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sub_category_id');
                $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
                $table->integer('type'); // 1 for video, 2 for gif
                $table->integer('free_paid')->default(0); // 1 for paid, 0 for free
                $table->text('path'); // video path
                $table->string('thumb')->nullable(); // thumbnail path
                $table->string('lable');
                $table->string('lablebg');
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
        Schema::dropIfExists('videogif');
    }
};
