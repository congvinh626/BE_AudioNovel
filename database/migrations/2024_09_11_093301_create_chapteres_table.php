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
        Schema::create('chapteres', function (Blueprint $table) {
            $table->id();
            $table->integer('chapter');
            $table->string('title');
            $table->text('content');
            $table->integer('novel_id');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chapteres', function (Blueprint $table) {
            //
        });
    }
};
