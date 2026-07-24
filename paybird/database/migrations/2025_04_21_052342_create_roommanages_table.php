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
        Schema::create('roommanages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('block_id'); // Block the room belongs to
            $table->unsignedBigInteger('room_type'); // Room type (linked to Types of Rooms)
            $table->string('room_number'); // Room number
            $table->timestamps();

            // Foreign keys
            $table->foreign('block_id')->references('id')->on('blocks')->onDelete('cascade');
            $table->foreign('room_type')->references('id')->on('types_of_rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roommanages');
    }
};
