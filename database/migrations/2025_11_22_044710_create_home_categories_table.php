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
        if (!Schema::hasTable('home_categories')) {
            Schema::create('home_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sub_category_id');
                $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
                $table->string('title');
                $table->smallInteger('sequence')->default('0');
                $table->tinyInteger('status')->default('0');
                $table->tinyInteger('is_show_on_home')->default('0');
                $table->tinyInteger('is_new')->default('0');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_categories');
    }
};
