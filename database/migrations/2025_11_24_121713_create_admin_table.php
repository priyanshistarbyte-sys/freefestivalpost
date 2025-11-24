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
        if (!Schema::hasTable('admin')) {
            Schema::create('admin', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('business_name')->nullable();
                $table->text('photo');
                $table->string('mobile',14)->unique();
                $table->string('email')->unique();
                $table->string('password');
                $table->string('b_email',50);
                $table->string('b_mobile2',12);
                $table->string('b_website',50);
                $table->integer('ispaid');
                $table->date('expdate')->nullable();
                $table->integer('planStatus')->nullable();
                $table->integer('gender');
                $table->string('role')->default('admin');
                $table->text('address');
                $table->integer('status');
                $table->text('note');
                $table->dateTime('last_login')->current_timestamp(); 
                $table->string('otp',10)->nullable();
                $table->string('business_category_id')->nullable();
                $table->integer('free_post_count')->default(0); 
                $table->string('gst_firm_name')->nullable();
                $table->string('gst_no',20)->nullable();
                $table->string('owner_name',50)->nullable();
                $table->string('owner_birth_date',50)->nullable();
                $table->string('business_anniversary_date',50)->nullable();
                $table->string('city',50)->nullable();
                $table->string('state',50)->nullable();
                $table->string('pincode',15)->nullable();
                $table->string('lang',10)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
