<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string player_id
 * @property string game_id
 * @property int number_of_moves
 * @property string pawn_id
 * @property bool is_sent_back
 * @property int current_position
 */

class Moves extends Model
{
    use HasFactory;

    public $incrementing = false;
}
