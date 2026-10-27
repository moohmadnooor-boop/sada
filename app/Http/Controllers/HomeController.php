<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * The landing page: hero, quote of the day, stats and a taste of the most loved quotes.
     */
    public function index(): View
    {
        $viewer = Auth::user();

        return view('home', [
            'quoteOfTheDay' => Quote::ofTheDay($viewer),
            'featuredQuotes' => Quote::query()->forCards($viewer)->orderByDesc('likes_count')->latest()->limit(6)->get(),
            'categories' => Category::withCount('quotes')->orderByDesc('quotes_count')->get(),
            'books' => Book::withCount('quotes')->orderByDesc('quotes_count')->limit(4)->get(),
            'stats' => [
                'quotes' => Quote::count(),
                'users' => User::count(),
                'likes' => DB::table('likes')->count(),
            ],
        ]);
    }
}
