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
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('admin')->onDelete('cascade');
                $table->double('amount', 12, 2);
                $table->date('date');
                $table->text('transactionid');
                $table->string('status', 100);
                $table->integer('packageid');
                $table->double('price', 12, 2);
                $table->integer('month');
                $table->dateTime('created_at');
                $table->tinyInteger('ref_status')->default(0)->comment('0-active, 1-refund, 2-admin-free');
                $table->string('refund_id', 30)->nullable(); 
                $table->dateTime('refundDate')->nullable(); 
                $table->tinyInteger('userRole')->nullable(); 
                $table->string('referral_code', 50)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
