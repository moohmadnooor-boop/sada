<?php

namespace App\Models;

use Database\Factories\BookFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'slug', 'author', 'description', 'era', 'cover_color'])]
class Book extends Model
{
    /** @use HasFactory<BookFactory> */
    use HasFactory;

    /**
     * Source and licence of each real cover (all from Wikimedia Commons), shown as the image's tooltip.
     *
     * @var array<string, string>
     */
    public const COVER_CREDITS = [
        'the-prophet' => 'The Prophet (Gibran), 1923 first edition — Public domain — Wikimedia Commons',
        'diwan-al-mutanabbi' => 'ديوان المتنبي بشرح الواحدي، 1861 — Public domain — Wikimedia Commons',
        'al-shawqiyyat' => 'الشوقيات — أحمد شوقي، مؤسسة هنداوي — CC BY 4.0 — Wikimedia Commons',
        'muqaddimah' => "La Muqaddima d'Ibn Khaldoun — Riad Salih — CC BY 4.0 — Wikimedia Commons",
        'masnavi' => 'Opening page of the Masnavi, Shiraz c. 1460, Bodleian Library MS Elliott 251 — Public domain — Wikimedia Commons',
    ];

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    /**
     * The real cover image for this book, if one was placed in public/images/books/{slug}.{jpg|jpeg|png|webp}.
     * Books without a file fall back to the generated Sada cover.
     */
    public function coverUrl(): ?string
    {
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
            $path = "images/books/{$this->slug}.{$extension}";

            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        return null;
    }

    public function coverCredit(): ?string
    {
        return self::COVER_CREDITS[$this->slug] ?? null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
