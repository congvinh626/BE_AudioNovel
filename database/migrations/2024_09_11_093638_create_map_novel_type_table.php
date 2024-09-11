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
        Schema::create('map_novel_type', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('novel_id');
            $table->foreign('type_id')->references('id')->on('type_novels')->onDelete('cascade');
            $table->foreign('novel_id')->references('id')->on('novels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('map_novel_type', function (Blueprint $table) {
            //
        });
    }
};
