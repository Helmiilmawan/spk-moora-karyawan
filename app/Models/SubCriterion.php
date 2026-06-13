<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Criterion;

class SubCriterion extends Model
{
    protected $table = 'sub_criteria';

    protected $fillable = [
        'criterion_id',
        'value',
        'label'
    ];

    public function criterion()
    {
        return $this->belongsTo(Criterion::class, 'criteria_id', 'id');
    }
}
