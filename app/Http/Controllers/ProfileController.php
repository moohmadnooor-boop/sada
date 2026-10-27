<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(User $user): View
    {
        $user->loadCount(['quotes', 'followers', 'following']);

        return view('profile.show', [
            'user' => $user,
            'quotes' => Quote::query()->forCards(Auth::user())->where('user_id', $user->id)->latest()->paginate(12),
            'likesReceived' => DB::table('likes')->join('quotes', 'quotes.id', '=', 'likes.quote_id')->where('quotes.user_id', $user->id)->count(),
            'isFollowing' => Auth::check() && Auth::user()->isFollowing($user),
        ]);
    }

    public function edit(): View
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $user->update($request->validated());

        return redirect()->route('profile.show', $user)->with('status', 'تم تحديث ملفك الشخصي.');
    }
}
