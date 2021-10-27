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
        'rundown_rows_id',
        'title',
        'type',
        'source',
        'delay',
        'duration',
        'data',
        'locked_by',
        'locked_at',
    ];

    public function rundown_rows()
    {
        return $this->belongsTo(Rundown_rows::class);
    }
}
