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
        Schema::create('exam_papers', function (Blueprint $table) {
         $table->id();
            
            // Foreign keys
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('programme_id');
            $table->unsignedBigInteger('question_id');
            
            // Timestamps
          
            
            // Indexes
       
            // Foreign key constraints
            $table->foreign('department_id')
                  ->references('id')
                  ->on('departments')
                  ->onDelete('cascade');
                  
            $table->foreign('programme_id')
                  ->references('id')
                  ->on('programmes')
                  ->onDelete('cascade');
                  
            $table->foreign('question_id')
                  ->references('id')
                  ->on('questions')
                  ->onDelete('cascade');
              $table->timestamps();
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_papers');
    }
};
