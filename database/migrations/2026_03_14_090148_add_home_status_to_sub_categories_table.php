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
     
        if (Schema::hasTable('sub_categories') && !Schema::hasColumn('sub_categories','home_status','is_show_on_home','is_new','sequence')) {
            Schema::table('sub_categories', function (Blueprint $table) {
                $table->integer('home_status')->default('0')->after('plan_auto');
                $table->integer('is_show_on_home')->default('0')->after('home_status');
                $table->integer('is_new')->default('0')->after('is_show_on_home');
                $table->integer('sequence')->default('0')->after('is_new');
            });
        }
    }

               

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            //
        });
    }
};
