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
             $table->time('checkout_time')->nullable()->after('hostel_attendence');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nomination_participants', function (Blueprint $table) {
            $table->dropColumn('checkout_time');

        });
    }
};
