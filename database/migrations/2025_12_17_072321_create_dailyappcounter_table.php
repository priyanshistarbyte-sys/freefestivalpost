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
         if (!Schema::hasTable('dailyappcounter')) {
            Schema::create('dailyappcounter', function (Blueprint $table) {
                $table->id();
                $table->text('package_name')->nullable();
                $table->text('device_id')->nullable();
                $table->date('date');
                $table->integer('new')->default(0);
                $table->bigInteger('impression')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dailyappcounter');
    }
};
