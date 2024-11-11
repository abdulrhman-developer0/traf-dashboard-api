<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PermissionGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'module',

    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly($this->fillable);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
