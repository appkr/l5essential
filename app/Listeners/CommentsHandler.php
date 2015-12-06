<?php

namespace App\Listeners;

use App\Comment;

class CommentsHandler
{
    protected $to = [];

    /**
     * Handle the event.
     *
     * @param \App\Comment $comment
     */
    public function handle(Comment $comment)
    {
        if ($comment->commentable->notification) {
            // get the Article author's email and append to the recipients array.
            $this->to[] = $comment->commentable->author->email;
        }

        // get email address lists from the comments and append to the recipients array.
        $this->findEmail($comment);

        // Remove duplicate email address.
        $to = array_unique($this->to);
        $subject = 'New comment';

        return \Mail::send('emails.new-comment', compact('comment'), function($m) use($to, $subject) {
            return $m->to($to)->subject($subject);
        });
    }

    /**
     * Recursively find email address from the comments and push them to recipients list.
     *
     * @param \App\Comment $comment
     */
    protected function findEmail(Comment $comment)
    {
        if ($comment->parent) {
            $this->to[] = $comment->parent->author->email;

            return $this->findEmail($comment->parent);
        }

        return;
    }
}
