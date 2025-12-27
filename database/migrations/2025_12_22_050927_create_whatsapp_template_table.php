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
         if (!Schema::hasTable('whatsapp_template')) {
            Schema::create('whatsapp_template', function (Blueprint $table) {
                $table->id();
                $table->string('tamp_name')->nullable();
                $table->string('template')->nullable();
                $table->string('type')->nullable();
                $table->integer('status');
                $table->text('media')->nullable();
                $table->integer('param');
                $table->string('lang')->nullable();
                $table->text('note')->nullable();
                $table->integer('bulk_status');
                $table->integer('sort');
                $table->dateTime('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_template');
    }
};
