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
        if (!Schema::hasTable('subscription_plans')) {
            Schema::create('subscription_plans', function (Blueprint $table) {
                $table->id('id');
                $table->string('plan_name');
                $table->string('special_title')->nullable();
                $table->string('duration');
                $table->string('duration_type');
                $table->float('price');
                $table->float('discount_price');
                $table->float('discount')->default(0.0);
                $table->integer('status');
                $table->integer('sequence');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
