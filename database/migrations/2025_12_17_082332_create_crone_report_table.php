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
        if (!Schema::hasTable('crone_report')) {
            Schema::create('crone_report', function (Blueprint $table) {
                $table->id();
                $table->text('funcation');
                $table->text('title');
                $table->text('type');
                $table->text('count');
                $table->dateTime('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crone_report');
    }
};
