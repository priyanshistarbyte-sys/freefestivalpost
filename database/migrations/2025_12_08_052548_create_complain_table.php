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
          if (!Schema::hasTable('complain')) {
            Schema::create('complain', function (Blueprint $table) {
                $table->id();
                $table->string('complain_id');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('admin')->onDelete('cascade');
                $table->string('subject');
                $table->text('message');
                $table->text('reply');
                $table->string('remark')->nullable()->comment('Only user for admin');;
                $table->string('status')->default('0')->comment('0: Pending, 1: On Progress, 2: Hold, 3: Solved');
                $table->timestamps();
            });
         }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complain');
    }
};
