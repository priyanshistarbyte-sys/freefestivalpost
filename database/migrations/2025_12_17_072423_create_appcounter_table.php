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
        if (!Schema::hasTable('appcounter')) {
            Schema::create('appcounter', function (Blueprint $table) {
                $table->id();
                $table->text('package_name')->nullable();
                $table->date('date');
                $table->bigInteger('active')->default(0);
                $table->bigInteger('new')->default(0);
                $table->bigInteger('impression')->default(0);
                $table->dateTime('updated_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appcounter');
    }
};
