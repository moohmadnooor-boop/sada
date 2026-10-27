<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Models\Category;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class QuoteController extends Controller
{
    /**
     * Display all quotes with search, category filter and sorting.
     */
    public function index(Request $request): View
    {
        $sort = array_key_exists($request->query('sort'), Quote::SORTS) ? $request->query('sort') : 'latest';
        $category = $request->filled('category') ? Category::where('slug', $request->query('category'))->first() : null;
        $search = (string) $request->query('q', '');

        $quotes = Quote::query()
            ->forCards(Auth::user())
            ->search($search)
            ->inCategory($category)
            ->sortBy($sort)
            ->paginate(12)
            ->withQueryString();

        return view('quotes.index', [
            'quotes' => $quotes,
            'categories' => Category::orderBy('id')->get(),
            'activeCategory' => $category,
            'sort' => $sort,
            'search' => $search,
        ]);
    }

    /**
     * Display one quote with its comments.
     */
    public function show(Quote $quote): View
    {
        $quote = Quote::query()->forCards(Auth::user())->findOrFail($quote->id);

        return view('quotes.show', [
            'quote' => $quote,
            'comments' => $quote->comments()->with('user')->latest()->get(),
            'related' => Quote::query()->forCards(Auth::user())
                ->whereKeyNot($quote->id)
                ->when($quote->category_id, fn ($q) => $q->where('category_id', $quote->category_id))
                ->inRandomOrder()
                ->limit(3)
                ->get(),
        ]);
    }

    /**
     * Store a newly created quote.
     */
    public function store(QuoteRequest $request): RedirectResponse
    {
        Auth::user()->quotes()->create($request->validated());

        return redirect()->route('quotes.index')->with('status', 'تم نشر اقتباسك، وصل صداه للجميع ✨');
    }

    /**
     * Update the specified quote.
     */
    public function update(QuoteRequest $request, Quote $quote): RedirectResponse
    {
        Gate::authorize('update', $quote);

        $quote->update($request->validated());

        return redirect()->back()->with('status', 'تم تعديل الاقتباس.');
    }

    /**
     * Remove the specified quote immediately.
     */
    public function destroy(Request $request, Quote $quote): RedirectResponse|JsonResponse
    {
        Gate::authorize('delete', $quote);

        $quote->delete();

        if ($request->expectsJson()) {
            return response()->json(['deleted' => true]);
        }

        return redirect()->route('quotes.index')->with('status', 'تم حذف الاقتباس.');
    }
}
