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
        Schema::create('budgets', function (Blueprint $table) {
         $table->id();

        $table->unsignedBigInteger('programme_id');
        $table->unsignedBigInteger('menu_id');
        $table->unsignedBigInteger('submenu_id');

        $table->decimal('price', 10, 2)->default(0);
        $table->integer('quantity')->default(0);
        $table->decimal('total', 12, 2)->default(0);

        $table->timestamps();

        $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('cascade');
        $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        $table->foreign('submenu_id')->references('id')->on('submenus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
