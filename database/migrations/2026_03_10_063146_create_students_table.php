<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('department');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->integer('year_level');
            $table->date('enrollment_date');
            $table->enum('status', ['Active', 'Inactive', 'Graduated', 'Dropped'])->default('Active');
            $table->integer('age');
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};