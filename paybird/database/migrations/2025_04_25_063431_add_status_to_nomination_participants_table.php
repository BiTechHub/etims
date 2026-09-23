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
        Schema::table('nomination_participants', function (Blueprint $table) {
            $table->string('status')->nullable()->default('N')->after('state');
            $table->string('attendence')->nullable()->default('N')->after('status');
            $table->string('hostel_attendence')->nullable()->default('N')->after('attendence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nomination_participants', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('attendence');
        });
    }
};
