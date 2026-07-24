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
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');

            $table->string('title'); // will hold either selected or custom title
            $table->enum('location', ['In-House', 'On-Location']);
            $table->string('venue');
            $table->foreignId('sponsor_id')->constrained('sponsors')->onDelete('cascade');

            $table->string('department_name')->nullable(); // from dropdown
            $table->string('department_input')->nullable(); // custom input
          
            // Fee section
            $table->boolean('participant_fee_check')->default(false);
            $table->decimal('participant_fee', 10, 2)->nullable();
            $table->boolean('program_fee_check')->default(false);
            $table->decimal('program_fee', 10, 2)->nullable();

            // Dates and session
            $table->date('from_date');
            $table->date('to_date');
            $table->string('duration')->nullable();
            $table->time('session_start')->nullable();
            $table->time('session_end')->nullable();

            // Additional notes
            $table->text('fee_structure')->nullable();
            $table->enum('status', ['NotAnnounce', 'Announced','Canceled','Postponed'])->default('NotAnnounce');
            $table->date('announced_on')->nullable();
            $table->boolean('last_nomination')->default(false);
            $table->date('last_nomination_date')->nullable();
            $table->string('strength')->nullable();
            $table->foreignId('class_room_id')->constrained('classes')->onDelete('cascade');

            $table->string('boarding_plan')->nullable();
            $table->decimal('max_disc_amt', 10, 2)->nullable();
            $table->foreignId('prog_dir_1')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('prog_dir_2')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('clientele_type', ['Normal', 'Mixed'])->nullable();
            $table->string('clientele')->nullable();
            $table->string('claim_ref')->nullable();
            $table->text('remarks')->nullable();
            $table->string('announcement_letter')->nullable();
            $table->string('nomination_form')->nullable();
            $table->string('pcr')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
