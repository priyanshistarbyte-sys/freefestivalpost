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
        if (Schema::hasTable('tamplet') && !Schema::hasColumn('tamplet', 'idx_event_event_date','idx_event_date','idx_event')) {
            Schema::table('tamplet', function (Blueprint $table) {
                $table->index(['event', 'event_date'], 'idx_event_event_date');
                $table->index('event_date', 'idx_event_date');
                $table->index('event', 'idx_event');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tamplet', function (Blueprint $table) {
            $table->dropIndex('idx_event_event_date');
            $table->dropIndex('idx_event_date');
            $table->dropIndex('idx_event');
        });
    }
};
