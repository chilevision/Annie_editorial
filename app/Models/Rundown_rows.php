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
        'locked_by',
        'locked_at',
        'script_locked_by',
        'script_locked_at',
        'notes_locked_by',
        'notes_locked_at',
    ];

    public function rundown_meta_rows()
    {
        return $this->hasMany(Rundown_meta_rows::class)->orderBy('delay', 'asc');
    }

    public function rundowns()
    {
        return $this->belongsTo(Rundowns::class);
    }
}
