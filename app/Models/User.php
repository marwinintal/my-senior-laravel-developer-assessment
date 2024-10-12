<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Events\UserSaved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prefixname',
        'firstname',
        'middlename',
        'lastname',
        'suffixname',
        'username',
        'photo',
        'type',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($user) {
            // Dispatch the UserSaved event whenever a User model is saved
            event(new UserSaved($user));
        });

        // static::updated(function ($user) {
        //     // Dispatch the UserSaved event whenever a User model is updated
        //     event(new UserSaved($user));
        // });
    }

    public function getAvatarAttribute()
    {
        return Storage::url($this->photo);
    }

    public function getFullnameAttribute(): string
    {
        if (!is_null($this->middlename) || $this->middlename !== '') {
            $middle_initial = strtoupper(substr($this->middlename, 0, 1));
            return "{$this->firstname} {$middle_initial}. {$this->lastname}";
        }

        return "{$this->firstname} {$this->lastname}";
    }

    public function getMiddleinitialAttribute(): string
    {
        return $this->middlename ? strtoupper(substr($this->middlename, 0, 1)) : null;
    }

    protected function getGenderFromPrefixAttribute()
    {
        if (!$this->prefixname) {
            return null;
        }

        // Customize this logic based on your specific prefix values
        $prefixname = strtolower($this->prefixname);

        if (in_array($prefixname, ['mr'])) {
            return 'male';
        }

        if (in_array($prefixname, ['ms', 'mrs'])) {
            return 'female';
        }

        return null;
    }

    public function details()
    {
        return $this->hasMany(Detail::class);
    }
}
