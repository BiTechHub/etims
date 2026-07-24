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
        Schema::create('_create_firm_epbgs_table', function (Blueprint $table) {
             $table->id();
            $table->foreignId('firm_id')->constrained('firm_registrations');
            $table->string('bg_no');
            $table->date('bg_date');
            $table->date('valid_date');
            $table->string('ifsc_code', 11);
            $table->string('bank_name');
            $table->text('branch_address');
            $table->string('contact_number');
            $table->string('bank_email');
            $table->string('certificate_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_create_firm_epbgs_table');
    }
};
