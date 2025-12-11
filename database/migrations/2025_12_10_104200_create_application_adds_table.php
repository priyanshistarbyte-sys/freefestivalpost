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
        if (!Schema::hasTable('application_add')) {
            Schema::create('application_add', function (Blueprint $table) {
                $table->id();
                $table->string('app_name');
                $table->string('app_package_name');
                $table->text('admob_main_id')->nullable();
                $table->text('fb_main_id')->nullable();
                $table->integer('status')->default('0');
                $table->integer('adclick')->default('0');
                $table->integer('mode')->default('0')->comment('0-test, 1-live');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_add');
    }
};
