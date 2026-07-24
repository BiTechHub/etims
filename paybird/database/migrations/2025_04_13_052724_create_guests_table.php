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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->text('address')->nullable();
            $table->string('state');
            $table->string('city');
            $table->string('pincode');
            $table->string('account_no');
            $table->string('ifsc');
            $table->string('branch_name');
            $table->text('bank_address');
            $table->string('kyc')->nullable();
        
             // store file path or name
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
