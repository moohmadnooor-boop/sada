<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DiscoverController extends Controller
{
    /**
     * The most liked quotes of the past seven days.
     */
    public function trending(): View
    {
        $quotes = Quote::query()
            ->forCards(Auth::user())
            ->withCount(['likers as weekly_likes_count' => fn (Builder $q) => $q->where('likes.created_at', '>=', now()->subDays(7))])
            ->orderByDesc('weekly_likes_count')
            ->orderByDesc('likes_count')
            ->limit(10)
            ->get();

        return view('quotes.trending', ['quotes' => $quotes]);
    }

    /**
     * Quotes shared by the people the current user follows.
     */
    public function feed(): View
    {
        $user = Auth::user();

        $quotes = Quote::query()
            ->forCards($user)
            ->whereIn('user_id', $user->following()->select('users.id'))
            ->latest()
            ->paginate(12);

        return view('quotes.feed', ['quotes' => $quotes]);
    }

    /**
     * Quotes the current user saved.
     */
    public function bookmarks(): View
    {
        $user = Auth::user();

        $quotes = Quote::query()
            ->forCards($user)
            ->whereHas('bookmarkers', fn (Builder $q) => $q->whereKey($user->id))
            ->latest()
            ->paginate(12);

        return view('quotes.bookmarks', ['quotes' => $quotes]);
    }
}
