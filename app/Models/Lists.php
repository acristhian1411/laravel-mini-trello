<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    protected $fillable = [
        'name',
        'board_id',
    ];

    public function board()
    {
        return $this->belongsTo(Boards::class);
    }

    public function tasks()
    {
        return $this->hasMany(Tasks::class);
    }
}
