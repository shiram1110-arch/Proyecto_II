<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios';

    protected $primaryKey = 'idUsuario';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellidoUno',
        'apellidoDos',
        'email',
        'telefono',
        'userName',
        'password',
        'idRol'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'password' => 'hashed'
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idRol');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'idUsuario');
    }
}