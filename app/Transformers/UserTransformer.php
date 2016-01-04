<?php

namespace App\Transformers;

use App\User;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include using url query string.
     * e.g. collection case -> ?include=comments:limit(5|1):order(created_at|desc)
     *      item case       -> ?include=author
     *
     * @var  array
     */
    protected $availableIncludes = ['articles', 'comments'];

    /**
     * Transform single resource.
     *
     * @param  \App\User $user
     * @return  array
     */
    public function transform(User $user)
    {
        return [
            'id'       => (int) $user->id,
            'name'     => $user->name,
            'email'    => $user->email,
            'signup'   => $user->created_at->toIso8601String(),
            'link'     => [
                'rel'  => 'self',
                'href' => route('api.users.show', $user->id),
            ],
            'articles' => (int) $user->articles->count(),
            'comments' => (int) $user->comments->count(),
        ];
    }

    /**
     * Include articles.
     *
     * @param  \App\User                     $user
     * @param  \League\Fractal\ParamBag|null $params
     * @return  \League\Fractal\Resource\Collection
     * @throws  \Exception
     */
    public function includeArticles(User $user, ParamBag $params = null)
    {
        list($limit, $offset, $orderCol, $orderBy) = $this->calculateParams($params);

        $articles = $user->articles()->limit($limit)->offset($offset)->orderBy($orderCol, $orderBy)->get();

        return $this->collection($articles, new \App\Transformers\ArticleTransformer);
    }

    /**
     * Include comments.
     *
     * @param  \App\User                     $user
     * @param  \League\Fractal\ParamBag|null $params
     * @return  \League\Fractal\Resource\Collection
     * @throws  \Exception
     */
    public function includeComments(User $user, ParamBag $params = null)
    {
        list($limit, $offset, $orderCol, $orderBy) = $this->calculateParams($params);

        $comments = $user->comments()->limit($limit)->offset($offset)->orderBy($orderCol, $orderBy)->get();

        return $this->collection($comments, new \App\Transformers\CommentTransformer);
    }
}
