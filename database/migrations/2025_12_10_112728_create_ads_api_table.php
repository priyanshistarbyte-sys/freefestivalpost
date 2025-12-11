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
        if (!Schema::hasTable('ads_api')) {
            Schema::create('ads_api', function (Blueprint $table) {
                $table->id();
                $table->string('ads_title');
                $table->text('ads_id');
                $table->unsignedBigInteger('app_id');
                $table->foreign('app_id')->references('id')->on('application_add')->onDelete('cascade'); 
                $table->integer('ads_type')->default('1');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads_api');
    }
};
