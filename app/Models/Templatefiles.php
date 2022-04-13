<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Templatefiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'size',
        'type',
        'modified_at'
    ];
}
