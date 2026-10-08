<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    public $timestamps = false; // Manejaremos fecha_hora manualmente o con la BD

    protected $fillable = [
        'usuario',
        'ip',
        'tipo_transaccion',
        'descripcion',
        'fecha_hora'
    ];
}