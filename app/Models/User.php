<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject as ContractsJWTSubject;

class User extends Authenticatable implements JWTSubject{
    use HasFactory,Notifiable;

    public $timestamps=false;
    protected $hidden=['password','token'];
    protected $fillable = [
        'name',
        'lastName',
        'login',
        'password',
        'profileImg',
        'email',
        'idFacebook',
        'idGoogle'
    ];

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }
    
}
