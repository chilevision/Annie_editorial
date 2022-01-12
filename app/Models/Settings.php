<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class Settings extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'max_rundown_lenght',
        'videoserver_name',
        'videoserver_ip',
        'videoserver_port',
        'videoserver_channel',
        'templateserver_name',
        'templateserver_ip',
        'templateserver_port',
        'templateserver_channel',
        'pusher_channel',
        'logo_path',
        'colors',
        'mixer_inputs',
        'mixer_keys',
        'sso',
        'user_ttl',
        'media_updated',
        'templates_updated',
    ];
}
