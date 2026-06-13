<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'image',
    ];

    /**
     * Relasi ke tabel ratings
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Relasi ke hasil perhitungan MOORA
     */
    public function result()
    {
        return $this->hasOne(Result::class);
    }

    /**
     * Mengambil nilai berdasarkan kriteria
     */
    public function ratingByCriterion(int $criterionId)
    {
        return $this->ratings
            ->where('criterion_id', $criterionId)
            ->first();
    }
}