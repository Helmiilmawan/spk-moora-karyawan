<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = ['alternative_id', 'yi', 'rank', 'details'];

    // WAJIB TAMBAHKAN INI:
    protected $casts = [
        'details' => 'array',
    ];

    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }
}