<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AvailableTime extends Model
{
    use HasFactory;
    protected $fillable=[
        'moshaver_id',
        'date',
        'start_time',
        'end_time',
        'duration'
    ];
    public function moshaver(){
        return $this->belongsTo(Moshaver::class);
    }
    public function reservations(){
        return $this->hasMany(Reservation::class);
    }
}
