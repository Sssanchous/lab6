<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Comment;

class Card extends Model
{
    use HasFactory, SoftDeletes;
    
    const CATEGORIES = [
        'sedan' => 'Седан',
        'suv' => 'Внедорожник',
        'coupe' => 'Купе',
        'hatchback' => 'Хэтчбек',
        'convertible' => 'Кабриолет',
        'wagon' => 'Универсал',
        'limousine' => 'Лимузин',
        'sports' => 'Спортивный',
        'luxury' => 'Люкс',
        'electric' => 'Электромобиль',
    ];

    protected $fillable = [
        'title',
        'brand',
        'model',
        'year',
        'category',
        'description',
        'image_path',
        'fun_fact_content',
        'horsepower',
        'price',
        'user_id', // ← обязательно
    ];

    protected $casts = [
        'year' => 'integer',
        'horsepower' => 'integer',
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Атрибуты
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2, '.', ' ') . ' ₽';
    }

    public function getFullNameAttribute()
    {
        return "{$this->brand} {$this->model} ({$this->year})";
    }

    public function getImageUrlAttribute()
    {
        if ($this->image_path && file_exists(public_path($this->image_path))) {
            return asset($this->image_path);
        }
        return asset('images/default-car.jpg');
    }

    public function getCategoryNameAttribute()
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    /**
     * Позволяет Route Model Binding находить даже мягко удалённые карточки.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? $this->getRouteKeyName(), $value)
                    ->withTrashed()
                    ->firstOrFail();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}