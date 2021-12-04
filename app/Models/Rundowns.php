<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rundowns extends Model
{
    use HasFactory;

    protected $table = 'rundowns';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'owner',
        'sortable',
        'loaded',
        'starttime',
        'stoptime',
        'duration',
    ];

    public function rundown_rows()
    {
        return $this->hasMany(Rundown_rows::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'rundown_user');
    }

    public function owner()
    {
        return $this->hasOne(User::class);
    }
}
