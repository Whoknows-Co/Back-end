<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoshaverAlt extends Model
{
    protected $fillable = [
        'moshaver_first_name',
        'moshaver_last_name',
        'address',
        'description',
        'more_description',
        'institute_name',
        'degree',
        'subject',
        'first_slot',
        'best_students',
        'contact',
    ];
    protected $casts = [
        'best_students' => 'array', // Casts JSON to PHP array
        'contact' => 'array',       // Casts JSON to PHP array
    ];
}
