<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{

    protected $fillable = [
        'user_id',
        'email',
        'ip',
        'country',
        'city',
        'platform',
        'platform_version',
        'browser',
        'browser_version',
        'is_desktop',
        'is_phone',
        'is_robot',
        'device_name',
        'user_agent',
        'is_success',
        'is_active',
        'banned_until'
    ];

}
