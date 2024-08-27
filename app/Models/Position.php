<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'report_to',
    ];

    public function parentPosition()
    {
        return $this->belongsTo(Position::class, 'report_to');
    }

    public function childPositions()
    {
        return $this->hasMany(Position::class, 'report_to');
    }
}
