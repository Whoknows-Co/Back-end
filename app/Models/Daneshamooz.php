<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Daneshamooz extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table='daneshamooz';
    protected $fillable=[
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password'
    ];
    protected $hidden=[
        'password',
        'remember_token'
    ];
    protected function casts():array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getJWTIdentifier(){
        return $this->getKey();
    }
    public function getJWTCustomClaims(){
        return [];
    }

}
