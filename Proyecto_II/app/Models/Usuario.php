<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
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
        'password'
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