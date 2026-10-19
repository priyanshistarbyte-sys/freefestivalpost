<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin', function (Blueprint $table) {
            $table->index('role');
            $table->index(['role', 'ispaid', 'planStatus']);
            $table->index('created_at');
            $table->index('expdate');
        });

        Schema::table('notification', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('daily_post_count', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::table('admin', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['role', 'ispaid', 'planStatus']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['expdate']);
        });

        Schema::table('notification', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('daily_post_count', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['date']);
        });
    }
};
