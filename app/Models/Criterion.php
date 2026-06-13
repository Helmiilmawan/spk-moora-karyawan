<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Rating;
use App\Models\SubCriterion;

class Criterion extends Model
{
    protected $table = 'criteria';

    protected $fillable = [
        'code',
        'name',
        'type',
        'weight'
    ];

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'criterion_id', 'id');
    }

    // PERBAIKAN FOREIGN KEY
    public function subCriteria()
    {
        return $this->hasMany(SubCriterion::class, 'criterion_id', 'id');
    }
}