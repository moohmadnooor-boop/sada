<?php

namespace App\Policies;

use App\Models\Quote;
use App\Models\User;

class QuotePolicy
{
    /**
     * Only the person who shared a quote may edit it.
     */
    public function update(User $user, Quote $quote): bool
    {
        return $user->id === $quote->user_id;
    }

    /**
     * Only the person who shared a quote may delete it.
     */
    public function delete(User $user, Quote $quote): bool
    {
        return $user->id === $quote->user_id;
    }
}
