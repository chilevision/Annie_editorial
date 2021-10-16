<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rundowns;

class Rundown_rows extends Model
{
    use HasFactory;

    protected $table = 'rundown_rows';

    protected $primaryKey = 'id';

    protected $fillable = [
        'rundown_id',
        'position',
        'color',
        'story',
        'talent',
        'type',
        'source',
        'audio',
        'duration',
        'script',
        'autotrigg'
    ];

    public function rundowns()
    {
        return $this->belongsTo(Rundowns::class);
    }
}
