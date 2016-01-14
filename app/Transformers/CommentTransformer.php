<?php

namespace App\Transformers;

use App\Comment;
use Appkr\Api\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include using url query string.
     * e.g. collection case -> ?include=comments:limit(5|1):order(created_at|desc)
     *      item case       -> ?include=author
     *
     * @var  array
     */
    protected $availableIncludes = ['author'];

    /**
     * Transform single resource.
     *
     * @param  \App\Comment $comment
     * @return  array
     */
    public function transform(Comment $comment)
    {
        $id = optimus((int) $comment->id);
        return [
            'id'           => $id,
            'content_raw'  => strip_tags($comment->content),
            'content_html' => markdown($comment->content),
            'created'      => $comment->created_at->toIso8601String(),
            'vote'         => ['up' => (int) $comment->up_count, 'down' => (int) $comment->down_count],
            'link'         => [
                'rel'  => 'self',
                'href' => route('api.v1.comments.show', $id),
            ],
            'author'       => [
                'name' => $comment->author->name,
                'email' => $comment->author->email,
                'avatar' => 'http:' . gravatar_profile_url($comment->author->email),
            ],
        ];
    }

    /**
     * Include author.
     *
     * @param  \App\Comment $comment
     * @return  \League\Fractal\Resource\Item
     */
    public function includeAuthor(Comment $comment)
    {
        return $this->item($comment->author, new \App\Transformers\UserTransformer);
    }
}
