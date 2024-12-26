<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Reservation2 extends Model
{
    use HasFactory;
    protected $table='reservation2';
    protected $fillable=[
        'student_first_name',
        'student_last_name',
        'level',
        'subject',
        'date_birth',
        'phone_number',
        'moshaver_id',
        'date',
        'time',
        'status'
    ];
    public function moshaver(){
        return $this->belongsTo(Moshaver::class);
    }
}
