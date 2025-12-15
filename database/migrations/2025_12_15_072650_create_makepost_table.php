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
         if (!Schema::hasTable('makepost')) {
            Schema::create('makepost', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('tamp_id');
                $table->text('post')->nullable();
                $table->date('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();

            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('makepost');
    }
};
