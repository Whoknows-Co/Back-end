<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slots extends Model
{
    protected $fillable = ['moshaver_id', 'date', 'start_time','end_time', 'status'];

    public function moshaver()
    {
        return $this->belongsTo(Moshaver::class);
    }
}
