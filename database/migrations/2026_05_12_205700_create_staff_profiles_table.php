<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('qualification')->nullable();
            $table->date('joining_date')->nullable();
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_profiles');
    }
};
