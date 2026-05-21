<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    protected $table = 'clases';

    protected $primaryKey = 'idClase';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'diaSemana',
        'horario',
        'capacidad'
    ];

    protected $casts = [
        'horario' => 'datetime:H:i'
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'idClase');
    }
}