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
        'before_in_table',
        'color',
        'story',
        'talent',
        'cue',
        'type',
        'source',
        'audio',
        'duration',
        'script',
        'autotrigg',
        'locked',
        'script_locked',
        'cam_notes_locked',
    ];

    public function rundown_meta_rows()
    {
        return $this->hasMany(Rundown_meta_rows::class);
    }

    public function rundowns()
    {
        return $this->belongsTo(Rundowns::class);
    }
}
