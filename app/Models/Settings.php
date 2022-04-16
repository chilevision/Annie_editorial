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
        'company',
        'company_address',
        'company_country',
        'company_phone',
        'company_email',
        'max_rundown_lenght',
        'videoserver_name',
        'videoserver_ip',
        'videoserver_port',
        'videoserver_channel',
        'templateserver_name',
        'templateserver_ip',
        'templateserver_port',
        'templateserver_channel',
        'backgroundserver_channel',
        'include_background',
        'pusher_channel',
        'logo_path',
        'colors',
        'mixer_inputs',
        'mixer_keys',
        'sso',
        'user_ttl',
        'email_address',
        'email_name',
        'email_subject',
        'removal_email_body',
        'media_updated',
        'templates_updated',
    ];
}
