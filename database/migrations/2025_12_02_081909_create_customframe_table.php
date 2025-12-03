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
        if (!Schema::hasTable('customframe')) {
            Schema::create('customframe', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('admin')->onDelete('cascade');
                $table->string('frame_name');
                $table->string('image');
                $table->integer('free_paid')->default('1');
                $table->integer('status')->default('0');
                $table->text('data')->nullable();
                $table->text('logosection')->nullable();
                $table->timestamps();
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customframe');
    }
};
