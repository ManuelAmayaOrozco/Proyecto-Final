<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Clase que representa una imagen de un insecto.
 */
class InsectPhoto extends Model
{

    use HasFactory;

    protected $fillable = ['path'];

    /**
     * El Insecto al que pertenece la imagen.
     */
    public function insect(): BelongsTo {
        return $this->belongsTo(Insect::class);
    }
}
