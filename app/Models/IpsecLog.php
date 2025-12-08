<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpsecLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'mode',
        'encryption',
        'auth',
        'ip_origen',
        'ip_destino',
        'resultado'
    ];

}
