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
        Schema::create('feedbackrespones', function (Blueprint $table) {
               $table->id();

        // Foreign key to participant
        $table->foreignId('participant_id')
            ->constrained('nomination_participants')
            ->onDelete('cascade');

        // Foreign key to submenu (feedback question)
        $table->foreignId('submenu_id')
            ->constrained('feedbacksubmenus')
            ->onDelete('cascade');

        // Rating (1=Poor, 2=Fair, 3=Good, 4=Very Good, 5=Excellent)
        $table->tinyInteger('rating');

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbackrespones');
    }
};
