<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maestro extends Model
{
    protected $fillable = ['nombre', 'apellido', 'correo', 'telefono', 'especialidad'];

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class);
    }
}
