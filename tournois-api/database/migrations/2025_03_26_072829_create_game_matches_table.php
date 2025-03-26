<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->dateTime('match_date');
            $table->string('status')->default('pending'); // pending, ongoing, completed
            $table->timestamps();
        });

        // Create pivot table for match players
        Schema::create('game_match_player', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_match_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('score')->nullable();
            $table->timestamps();
            
            // Ensure a player can't be added twice to the same match
            $table->unique(['game_match_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_match_player');
        Schema::dropIfExists('game_matches');
    }
};