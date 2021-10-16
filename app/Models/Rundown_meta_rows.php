<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rundown_meta_rows extends Model
{
    use HasFactory;

    protected $table = 'rundown_meta_rows';

    protected $primaryKey = 'id';

    protected $fillable = [
        'row_id',
        'title',
        'type',
        'source',
        'start',
        'duration',
        'data',
    ];
}
