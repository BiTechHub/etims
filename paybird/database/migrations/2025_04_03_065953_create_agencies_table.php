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
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agency_type_id');
            $table->string('name');
            $table->string('sponsor_bank')->nullable();
            $table->string('chairman')->nullable();
            $table->text('address')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('phone')->nullable();
            $table->string('emailid')->unique()->nullable();
            $table->string('country')->nullable();
            $table->string('fax')->nullable()->nullable();
            $table->string('cc_email')->nullable()->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('agency_type_id')->references('id')->on('agency_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};
