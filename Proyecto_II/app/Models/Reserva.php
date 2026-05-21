<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';

    protected $primaryKey = 'idReserva';

    public $timestamps = false;

    protected $fillable = [
        'idUsuario',
        'idClase',
        'fechaReserva',
        'estado'
    ];

    protected $casts = [
        'fechaReserva' => 'date'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }

    public function clase()
    {
        return $this->belongsTo(Clase::class, 'idClase');
    }
}
