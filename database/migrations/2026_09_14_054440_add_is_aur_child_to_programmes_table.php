<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->string('is_aur_child')->nullable()->after('unique_id');
            $table->index('is_aur_child'); // filter fast chalega
        });
    }

    public function down()
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->dropColumn('is_aur_child');
        });
    }
};