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
        Schema::create('novels', function (Blueprint $table) {
            $table->id();
            $table->text('thumbnail')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->integer('author_id')->nullable();
            $table->integer('chapter')->nullable();
            $table->integer('view')->nullable();
            $table->double('rating')->nullable();
            $table->integer('numberRating')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('novels', function (Blueprint $table) {
            //
        });
    }
};
