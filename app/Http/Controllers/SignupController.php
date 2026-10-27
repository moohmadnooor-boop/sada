<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SignupController extends Controller
{
    public function show(): View
    {
        return view('Authentication.SignUp', ['page' => 'Sign Up']);
    }

    public function signup(SignupRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('quotes.index')->with('status', 'أهلاً '.$user->name.'! شارك أول اقتباس لك بالضغط على زر +');
    }
}
