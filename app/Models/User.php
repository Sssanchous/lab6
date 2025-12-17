<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_friends',
            'user_id',
            'friend_id'
        )->withPivot('status')->withTimestamps();
    }

    public function acceptedFriends(): BelongsToMany
    {
        return $this->friends()
            ->wherePivot('status', 'accepted')
            ->whereExists(function ($q) {
                $q->selectRaw(1)
                ->from('user_friends as uf2')
                ->whereColumn('uf2.user_id', 'user_friends.friend_id')
                ->whereColumn('uf2.friend_id', 'user_friends.user_id')
                ->where('uf2.status', 'accepted');
            });
    }




    public function incomingFriendRequests(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_friends',
            'friend_id',
            'user_id'
        )->wherePivot('status', 'pending');
    }

    public function outgoingFriendRequests(): BelongsToMany
    {
        return $this->friends()->wherePivot('status', 'pending');
    }

    public function isFriendWith(User $user): bool
    {
        return $this->acceptedFriends()
            ->where('users.id', $user->id)
            ->exists();
    }

    public function hasSentFriendRequestTo(User $user): bool
    {
        return $this->outgoingFriendRequests()
            ->where('users.id', $user->id)
            ->exists();
    }

    public function hasIncomingFriendRequestFrom(User $user): bool
    {
        return $this->incomingFriendRequests()
            ->where('users.id', $user->id)
            ->exists();
    }
}
