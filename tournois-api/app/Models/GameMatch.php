<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GameMatch extends Model
{
    protected $fillable = [
        'tournament_id',
        'match_date',
        'status'
    ];

    protected $casts = [
        'match_date' => 'datetime'
    ];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'game_match_player')
                    ->withPivot('score')
                    ->withTimestamps();
    }
}