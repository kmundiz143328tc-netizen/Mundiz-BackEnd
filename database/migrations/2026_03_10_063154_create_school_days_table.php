<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_days', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->enum('day_type', ['class', 'holiday', 'event', 'suspension'])->default('class');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->float('attendance_rate')->default(0);
            $table->integer('students_present')->default(0);
            $table->integer('students_absent')->default(0);
            $table->string('school_year')->nullable();
            $table->string('semester')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_days');
    }
};