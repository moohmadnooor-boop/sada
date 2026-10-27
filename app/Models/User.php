<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'bio'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likedQuotes(): BelongsToMany
    {
        return $this->belongsToMany(Quote::class, 'likes')->withTimestamps();
    }

    public function bookmarkedQuotes(): BelongsToMany
    {
        return $this->belongsToMany(Quote::class, 'bookmarks')->withTimestamps();
    }

    /**
     * Users this user follows.
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    /**
     * Users who follow this user.
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->whereKey($user->id)->exists();
    }

    /**
     * The first letter of the name, shown inside the avatar circle.
     */
    public function initial(): string
    {
        return mb_substr(trim($this->name), 0, 1);
    }

    /**
     * A stable hue (0–359) derived from the user id so every avatar keeps its colour.
     */
    public function avatarHue(): int
    {
        return ($this->id * 47) % 360;
    }
}
