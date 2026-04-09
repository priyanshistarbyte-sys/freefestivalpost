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
         if (!Schema::hasTable('dailog')) {
            Schema::create('dailog', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('app_id');
                $table->foreign('app_id')->references('id')->on('application_add')->onDelete('cascade'); 
                $table->text('title')->nullable();
                $table->text('description')->nullable();
                $table->string('button1')->nullable();
                $table->string('button2')->nullable();
                $table->text('link')->nullable();
                $table->text('image')->nullable();
                $table->integer('appversion')->nullable();
                $table->string('forcefully')->nullable();
                $table->integer('other_forcefully')->nullable();
                $table->integer('isDisplay')->nullable();
                $table->integer('other_isDisplay')->nullable();
                $table->string('o_type')->nullable();
                $table->text('o_link')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dailog');
    }
};
