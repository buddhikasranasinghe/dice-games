<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('moves', function (Blueprint $table) {
            $table->string('id')->default(Str::uuid());
            $table->string('player_id')->foreign();
            $table->string('game_id')->foreign();
            $table->string('pawn_id')->foreign();
            $table->integer('number_of_moves');
            $table->integer('current_position');
            $table->boolean('is_sent_back');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moves');
    }
};
