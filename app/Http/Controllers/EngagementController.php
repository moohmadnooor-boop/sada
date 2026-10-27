<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EngagementController extends Controller
{
    /**
     * Like or unlike a quote.
     */
    public function toggleLike(Request $request, Quote $quote): JsonResponse|RedirectResponse
    {
        $result = Auth::user()->likedQuotes()->toggle($quote->id);
        $liked = count($result['attached']) > 0;

        return $this->respond($request, [
            'active' => $liked,
            'count' => $quote->likers()->count(),
        ]);
    }

    /**
     * Save or unsave a quote to the user's bookmarks.
     */
    public function toggleBookmark(Request $request, Quote $quote): JsonResponse|RedirectResponse
    {
        $result = Auth::user()->bookmarkedQuotes()->toggle($quote->id);

        return $this->respond($request, [
            'active' => count($result['attached']) > 0,
        ]);
    }

    /**
     * Follow or unfollow another user.
     */
    public function toggleFollow(Request $request, User $user): JsonResponse|RedirectResponse
    {
        abort_if($user->is(Auth::user()), 422, 'لا يمكنك متابعة نفسك.');

        $result = Auth::user()->following()->toggle($user->id);

        return $this->respond($request, [
            'active' => count($result['attached']) > 0,
            'count' => $user->followers()->count(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function respond(Request $request, array $payload): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json($payload);
        }

        return redirect()->back();
    }
}
