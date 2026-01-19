<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('videoanalytics')) {

            Schema::create('videoanalytics', function (Blueprint $table) {
                $table->id();           // auto increment primary key
                $table->date('date');
                $table->integer('count');
            });

            // ✅ Reset auto increment safely
            DB::statement('ALTER TABLE videoanalytics AUTO_INCREMENT = 1');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('videoanalytics');
    }
};
