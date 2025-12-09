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
        if (!Schema::hasTable('webhook_failed')) {
            Schema::create('webhook_failed', function (Blueprint $table) {
                $table->id('web_fail_id');
                $table->date('w_date');
                $table->string('w_event');
                $table->string('transaction_id');
                $table->float('w_amount',12,2)->default(0.0);
                $table->string('w_email');
                $table->string('w_mobile');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_failed');
    }
};
