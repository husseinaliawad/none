<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
    ];

    public function hasRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }


    public function channel()
    {
        return $this->hasOne(Channel::class);
    }


    public function owns(Video $video)
    {
        return $this->id == $video->channel->user_id;
    }


    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }


    public function subscribedChannels()
    {
        return $this->belongsToMany(Channel::class, 'subscriptions');
    }


    public function isSubscribedTo(Channel $channel)
    {
        return (bool) $this->subscriptions->where('channel_id', $channel->id)->count();
    }


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function progress(): HasOne
    {
        return $this->hasOne(UserProgress::class);
    }

    public function fanGroups(): BelongsToMany
    {
        return $this->belongsToMany(FanGroup::class, 'fan_group_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

}
