<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->decimal('midterm', 5, 2)->nullable();
            $table->decimal('finals', 5, 2)->nullable();
            $table->decimal('gwa', 5, 2)->nullable();
            $table->string('remarks')->nullable(); // Passed, Failed, Incomplete
            $table->string('school_year')->default('2024-2025');
            $table->string('semester')->default('1st');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};