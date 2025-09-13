<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    protected $fillable = ['nama', 'link', 'jenis', 'deskripsi', 'lokasi_id'];

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
