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
        if (!Schema::hasTable('sub_categories')) {
            Schema::create('sub_categories', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('category_id');
                    $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                    $table->string('is_parent')->default(0);
                    $table->unsignedBigInteger('is_child')->nullable()->default(null);
                    $table->string('parent_category')->nullable();
                    $table->text('image');
                    $table->date('event_date')->nullable();
                    $table->string('mtitle');
                    $table->string('mslug');
                    $table->integer('status');
                    $table->string('lable');
                    $table->string('lablebg');
                    $table->text('noti_banner')->nullable();
                    $table->text('mask')->nullable();
                    $table->text('noti_quote')->nullable();
                    $table->tinyInteger('plan_auto')->nullable();
                    $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
    }
};
