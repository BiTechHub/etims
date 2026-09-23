<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    DB::statement("ALTER TABLE programmes MODIFY COLUMN status ENUM('NotAnnounce','Announced','Canceled','Postpond','Reschedule') NOT NULL DEFAULT 'NotAnnounce'");
}

public function down()
{
    DB::statement("ALTER TABLE programmes MODIFY COLUMN status ENUM('NotAnnounce','Announced','Canceled','Postpond') NOT NULL DEFAULT 'NotAnnounce'");
}
};
