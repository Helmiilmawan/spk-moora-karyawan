<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessHistory extends Model
{
    protected $table = 'process_histories';

    protected $fillable = [
        'process_name',
        'process_date',
        'results_data'
    ];

    // Mengubah string JSON dari database menjadi Array PHP secara otomatis
    protected $casts = [
        'results_data' => 'array',
        'process_date' => 'datetime'
    ];
}