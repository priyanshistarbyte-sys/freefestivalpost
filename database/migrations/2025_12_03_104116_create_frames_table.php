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
         if (!Schema::hasTable('frames')) {
            Schema::create('frames', function (Blueprint $table) {
                $table->id();
                $table->string('frame_name');
                $table->integer('free_paid')->default(1);
                $table->integer('status')->default(1);
                $table->text('image');
                $table->longText('data')->nullable();
                $table->longText('logosection')->nullable();
                $table->unsignedBigInteger('sub_category_id')->nullable();
                $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frames');
    }
};
