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
        if (!Schema::hasTable('appslider')) {
            Schema::create('appslider', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('image')->nullable();
                $table->string('mid')->nullable();
                $table->string('sub')->nullable();
                $table->text('url')->nullable();
                $table->integer('status')->default(1);
                $table->tinyInteger('sort')->nullable();
                $table->date('festivalDate')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appslider');
    }
};
