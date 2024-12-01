<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Contracts\HasAccounts;
use App\Traits\IntractsWithAccount;
use App\Traits\UserTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia, HasAccounts
{
    use HasFactory, Notifiable, SoftDeletes;
    use InteractsWithMedia;
    use LogsActivity;
    use HasRoles;
    use HasApiTokens;
    use IntractsWithAccount, UserTypes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_activity',
        'account_type',
        'code',
        'expire_at'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email']);
    }


    // public function notifications()
    // {
    //     return $this->hasMany(Notification::class);
    // }

    public function generateCode()
    {
        $this->timestamps = false;

        do {
            $this->code = random_int(10000, 99999);
        } while (User::where('code', $this->code)->exists()); // Ensure the code is unique
        $this->expire_at = now()->addMinutes(20);

        $this->save();
    }
}
