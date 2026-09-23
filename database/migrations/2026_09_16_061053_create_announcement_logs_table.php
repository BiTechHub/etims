<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcement_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('programme_id');
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->string('email');
            $table->string('subject')->nullable();
            $table->longText('message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index('programme_id');
            $table->index('agency_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_logs');
    }
};