<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    // ==================== ОТНОШЕНИЯ ====================

    /**
     * Карточки пользователя
     */
    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    /**
     * Комментарии пользователя
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Друзья пользователя (отношение многие-ко-многим)
     */
    public function friends()
    {
        return $this->belongsToMany(User::class, 'user_friends', 'user_id', 'friend_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Пользователи, которые добавили текущего пользователя в друзья
     */
    public function friendOf()
    {
        return $this->belongsToMany(User::class, 'user_friends', 'friend_id', 'user_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    // ==================== МЕТОДЫ ДЛЯ РАБОТЫ С ДРУЗЬЯМИ ====================

    /**
     * Получить подтвержденных друзей
     */
    public function acceptedFriends()
    {
        return $this->friends()->wherePivot('status', 'accepted');
    }

    /**
     * Получить входящие заявки в друзья
     */
    public function incomingFriendRequests()
    {
        return $this->belongsToMany(User::class, 'user_friends', 'friend_id', 'user_id')
            ->wherePivot('status', 'pending');
    }

    /**
     * Получить исходящие заявки в друзья
     */
    public function outgoingFriendRequests()
    {
        return $this->friends()->wherePivot('status', 'pending');
    }

    /**
     * Проверить, является ли пользователь другом
     */
    public function isFriendWith(User $user)
    {
        return DB::table('user_friends')
            ->where(function($query) use ($user) {
                $query->where('user_id', $this->id)
                      ->where('friend_id', $user->id)
                      ->where('status', 'accepted');
            })
            ->orWhere(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('friend_id', $this->id)
                      ->where('status', 'accepted');
            })
            ->exists();
    }

    /**
     * Проверить, отправил ли пользователь заявку в друзья
     */
    public function hasSentFriendRequestTo(User $user)
    {
        return DB::table('user_friends')
            ->where('user_id', $this->id)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Проверить, есть ли входящая заявка в друзья
     */
    public function hasIncomingFriendRequestFrom(User $user)
    {
        return DB::table('user_friends')
            ->where('user_id', $user->id)
            ->where('friend_id', $this->id)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Добавить друга
     */
    public function addFriend(User $user)
    {
        if (!$this->isFriendWith($user) && $this->id !== $user->id) {
            $this->friends()->attach($user->id, ['status' => 'pending']);
            return true;
        }
        return false;
    }

    /**
     * Принять заявку в друзья
     */
    public function acceptFriendRequest(User $user)
    {
        $request = DB::table('user_friends')
            ->where('user_id', $user->id)
            ->where('friend_id', $this->id)
            ->where('status', 'pending')
            ->first();

        if ($request) {
            DB::table('user_friends')
                ->where('user_id', $user->id)
                ->where('friend_id', $this->id)
                ->update(['status' => 'accepted']);
            return true;
        }
        return false;
    }

    /**
     * Отклонить заявку в друзья
     */
    public function rejectFriendRequest(User $user)
    {
        return DB::table('user_friends')
            ->where('user_id', $user->id)
            ->where('friend_id', $this->id)
            ->where('status', 'pending')
            ->delete() > 0;
    }

    /**
     * Удалить друга
     */
    public function removeFriend(User $user)
    {
        return DB::table('user_friends')
            ->where(function($query) use ($user) {
                $query->where('user_id', $this->id)
                      ->where('friend_id', $user->id);
            })
            ->orWhere(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('friend_id', $this->id);
            })
            ->delete() > 0;
    }

    /**
     * Получить всех друзей (входящие + исходящие подтвержденные)
     */
    public function getAllFriends()
    {
        $friends1 = $this->friends()->wherePivot('status', 'accepted')->get();
        $friends2 = $this->friendOf()->wherePivot('status', 'accepted')->get();
        
        return $friends1->merge($friends2)->unique('id');
    }

    // ==================== ВСПОМОГАТЕЛЬНЫЕ МЕТОДЫ ====================

    /**
     * Проверка является ли пользователь администратором
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Получить количество карточек пользователя
     */
    public function getCardsCountAttribute()
    {
        return $this->cards()->count();
    }

    /**
     * Получить количество друзей
     */
    public function getFriendsCountAttribute()
    {
        return $this->getAllFriends()->count();
    }
}