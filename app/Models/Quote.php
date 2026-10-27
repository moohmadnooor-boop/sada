<?php

namespace App\Models;

use Database\Factories\QuoteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['content', 'author', 'category_id', 'book_id'])]
class Quote extends Model
{
    /** @use HasFactory<QuoteFactory> */
    use HasFactory;

    public const SORTS = [
        'latest' => 'الأحدث',
        'popular' => 'الأكثر إعجاباً',
        'oldest' => 'الأقدم',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    public function bookmarkers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }

    /**
     * Load everything a quote card needs in one go: author, category, counters,
     * and whether the current viewer already liked or saved it.
     */
    public function scopeForCards(Builder $query, ?User $viewer): Builder
    {
        $query->with(['user', 'category', 'book'])->withCount(['likers as likes_count', 'comments']);

        if ($viewer) {
            $query->withExists([
                'likers as liked_by_viewer' => fn (Builder $q) => $q->whereKey($viewer->id),
                'bookmarkers as bookmarked_by_viewer' => fn (Builder $q) => $q->whereKey($viewer->id),
            ]);
        }

        return $query;
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('content', 'like', "%{$term}%")
                ->orWhere('author', 'like', "%{$term}%")
                ->orWhereHas('user', fn (Builder $u) => $u->where('name', 'like', "%{$term}%"))
                ->orWhereHas('book', fn (Builder $b) => $b->where('title', 'like', "%{$term}%"));
        });
    }

    public function scopeInCategory(Builder $query, ?Category $category): Builder
    {
        return $category ? $query->where('category_id', $category->id) : $query;
    }

    public function scopeSortBy(Builder $query, string $sort): Builder
    {
        return match ($sort) {
            'popular' => $query->orderByDesc('likes_count')->latest(),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };
    }

    /**
     * Pick the same quote for everyone for the whole day, changing every midnight.
     */
    public static function ofTheDay(?User $viewer = null): ?self
    {
        $total = static::count();

        if ($total === 0) {
            return null;
        }

        $offset = crc32(now()->toDateString()) % $total;

        return static::query()->forCards($viewer)->orderBy('id')->skip($offset)->first();
    }
}
