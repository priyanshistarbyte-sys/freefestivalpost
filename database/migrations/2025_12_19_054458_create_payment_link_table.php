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
         if (!Schema::hasTable('payment_link')) {
            Schema::create('payment_link', function (Blueprint $table) {
                $table->id();
                $table->text('link');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('admin')->onDelete('cascade');
                $table->string('mobile')->nullable();
                $table->integer('attempts')->nullable();
                $table->dateTime('exp_date')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_link');
    }
};
