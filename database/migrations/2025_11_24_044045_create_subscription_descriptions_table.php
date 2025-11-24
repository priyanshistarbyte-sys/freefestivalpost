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
         if (!Schema::hasTable('subscription_descriptions')) {
            Schema::create('subscription_descriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('plan_id')->constrained('subscription_plans')->onDelete('cascade');
                $table->string('title');
                $table->string('sign')->default('0');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_descriptions');
    }
};
