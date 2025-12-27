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
        if (!Schema::hasTable('coupon_code')) {
            Schema::create('coupon_code', function (Blueprint $table) {
                $table->id();
                $table->string('title', 50);
                $table->string('name', 50);
                $table->string('code', 25);
                $table->integer('total_qty');
                $table->date('start_date');
                $table->date('end_date');
                $table->integer('total_days');
                $table->integer('total_count_user_apply');
                $table->string('note')->nullable();
                $table->integer('status');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_code');
    }
};
