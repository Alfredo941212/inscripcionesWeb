<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $fillable = ['maestro_id', 'curso_id', 'fecha'];

    public function maestro()
    {
        return $this->belongsTo(Maestro::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}

