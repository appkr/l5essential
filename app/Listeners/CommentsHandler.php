<?php

namespace App\Listeners;

use App\Comment;

class CommentsHandler
{
    /**
     * Handle the event.
     *
     * @param \App\Comment $comment
     */
    public function handle(Comment $comment)
    {
        $to[] = $comment->commentable->author->email;

        if ($comment->parent) {
            $to[] = $comment->parent->author->email;
        }

        $to = array_unique($to);
        $subject = 'New comment';

        return \Mail::send('emails.new-comment', compact('comment'), function($m) use($to, $subject) {
            $m->to($to)->subject($subject);
        });
    }
}
