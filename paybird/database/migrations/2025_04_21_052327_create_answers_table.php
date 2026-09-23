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
        Schema::create('answers', function (Blueprint $table) {
              $table->id();
        $table->unsignedBigInteger('programme_id');
        $table->unsignedBigInteger('participants_id');
        $table->unsignedBigInteger('question_id');
        $table->string('options');
        $table->timestamps();

        // Foreign keys (if applicable)
        $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('cascade');
        $table->foreign('participants_id')->references('id')->on('participants')->onDelete('cascade');
        $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
