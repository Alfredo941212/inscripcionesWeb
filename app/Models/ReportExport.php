<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'format',
        'name',
        'path',
        'filters',
        'size_bytes',
        'generated_by',
    ];

    protected $casts = [
        'filters' => 'array',
        'size_bytes' => 'integer',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
