<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Quote $quote): RedirectResponse
    {
        $quote->comments()->make($request->validated())->user()->associate(Auth::user())->save();

        return redirect()->to(route('quotes.show', $quote).'#comments')->with('status', 'تمت إضافة تعليقك.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return redirect()->back()->with('status', 'تم حذف التعليق.');
    }
}
