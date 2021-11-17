<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mediafiles extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'type',
        'size',
        'duration',
        'fps',
        'modified_at',
    ];
}
