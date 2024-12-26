<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable=[
        'daneshamooz_id',
        'moshaver_id',
        'date',
        'time',
        'status'
    ];
    protected function daneshamooz(){
        return $this->belongsTo(Daneshamooz::class);
    
    }
    protected function moshaver(){
        return $this->belongsTo(Moshaver::class);
    }
    public function availableTime(){
        return $this->belongsTo(AvailableTime::class);
    }
}
