<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * The library: every book with how many quotes were shared from it.
     */
    public function index(): View
    {
        return view('books.index', [
            'books' => Book::withCount('quotes')->orderByDesc('quotes_count')->orderBy('title')->get(),
        ]);
    }

    /**
     * One book with all the quotes shared from it.
     */
    public function show(Request $request, Book $book): View
    {
        $sort = array_key_exists($request->query('sort'), Quote::SORTS) ? $request->query('sort') : 'popular';

        $book->loadCount('quotes');

        return view('books.show', [
            'book' => $book,
            'sort' => $sort,
            'quotes' => Quote::query()
                ->forCards(Auth::user())
                ->where('book_id', $book->id)
                ->sortBy($sort)
                ->paginate(12)
                ->withQueryString(),
            'likesCount' => Quote::where('book_id', $book->id)->join('likes', 'likes.quote_id', '=', 'quotes.id')->count(),
        ]);
    }
}
