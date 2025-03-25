<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tournament extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'user_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}