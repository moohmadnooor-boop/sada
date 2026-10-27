<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Only the person who wrote a comment may delete it.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }
}
