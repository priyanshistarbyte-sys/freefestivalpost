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
        if (!Schema::hasTable('webhook_authorized')) {
            Schema::create('webhook_authorized', function (Blueprint $table) {
                $table->id();
                $table->date('date');
                $table->string('event');
                $table->string('transaction_id');
                $table->double('amount',12,2);
                $table->string('email');
                $table->string('mobile');
                $table->integer('status')->default(0);
                $table->dateTime('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_authorized');
    }
};
