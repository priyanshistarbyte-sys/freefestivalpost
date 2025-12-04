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
