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
        Schema::create('question_paper_basic_details', function (Blueprint $table) {
            $table->id();
            $table->string('programme_id');
            $table->string('total_marks');
            $table->string('passing_marks');
            $table->string('duration')->comment('in minutes');
            $table->string('exit_exam_date')->nullable();
            $table->string('entry_exam_date')->nullable();
            $table->string('entry_exam_time')->nullable();
            $table->string('exit_exam_time')->nullable();
            $table->string('exam_type')->default('entry');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_paper_basic_details');
    }
};
