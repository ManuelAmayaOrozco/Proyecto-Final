<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InsectPhoto extends Model
{

    use HasFactory;

    protected $fillable = ['path'];

    public function insect(): BelongsTo {
        return $this->belongsTo(Insect::class);
    }
}
