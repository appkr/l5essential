<?php

namespace App\Transformers;

use App\Article;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class ArticleTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include using url query string.
     * e.g. collection case -> ?include=comments:limit(5|1):order(created_at|desc)
     *      item case       -> ?include=author
     *
     * @var  array
     */
    protected $availableIncludes = ['comments', 'author', 'tags', 'attachments'];

    /**
     * Transform single resource.
     *
     * @param  \App\Article $article
     * @return  array
     */
    public function transform(Article $article)
    {
        $id = optimus((int) $article->id);

        $payload = [
            'id'           => $id,
            'title'        => $article->title,
            'content_raw'  => strip_tags($article->content),
            'content_html' => markdown($article->content),
            'created'      => $article->created_at->toIso8601String(),
            'view_count'   => (int) $article->view_count,
            'link'         => [
                'rel'  => 'self',
                'href' => route('api.v1.articles.show', $id),
            ],
            'comments'     => (int) $article->comments->count(),
            'author'       => [
                'name'   => $article->author->name,
                'email'  => $article->author->email,
                'avatar' => 'http:' . gravatar_profile_url($article->author->email),
            ],
            'tags'         => $article->tags->pluck('slug'),
            'attachments'  => (int) $article->attachments->count(),
        ];

        if ($fields = $this->getPartialFields()) {
            $payload = array_only($payload, $fields);
        }

        return $payload;
    }

    /**
     * Include comments.
     *
     * @param  \App\Article                  $article
     * @param  \League\Fractal\ParamBag|null $params
     * @return  \League\Fractal\Resource\Collection
     * @throws  \Exception
     */
    public function includeComments(Article $article, ParamBag $params = null)
    {
        $transformer = new \App\Transformers\CommentTransformer($params);

        $parsed = $this->getParsedParams();

        $comments = $article->comments()->limit($parsed['limit'])->offset($parsed['offset'])->orderBy($parsed['sort'], $parsed['order'])->get();

        return $this->collection($comments, $transformer);
    }

    /**
     * Include author.
     *
     * @param  \App\Article                 $article
     * @param \League\Fractal\ParamBag|null $params
     * @return \League\Fractal\Resource\Item
     */
    public function includeAuthor(Article $article, ParamBag $params = null)
    {
        return $this->item($article->author, new \App\Transformers\UserTransformer($params));
    }

    /**
     * Include tags.
     *
     * @param  \App\Article                  $article
     * @param  \League\Fractal\ParamBag|null $params
     * @return  \League\Fractal\Resource\Collection
     * @throws  \Exception
     */
    public function includeTags(Article $article, ParamBag $params = null)
    {
        $transformer = new \App\Transformers\TagTransformer($params);

        $parsed = $this->getParsedParams();

        $tags = $article->tags()->limit($parsed['limit'])->offset($parsed['offset'])->orderBy($parsed['sort'], $parsed['order'])->get();

        return $this->collection($tags, $transformer);
    }

    /**
     * Include attachments.
     *
     * @param  \App\Article                  $article
     * @param  \League\Fractal\ParamBag|null $params
     * @return  \League\Fractal\Resource\Collection
     * @throws  \Exception
     */
    public function includeAttachments(Article $article, ParamBag $params = null)
    {
        $transformer = new \App\Transformers\AttachmentTransformer($params);

        $parsed = $this->getParsedParams();

        $attachments = $article->attachments()->limit($parsed['limit'])->offset($parsed['offset'])->orderBy($parsed['sort'], $parsed['order'])->get();

        return $this->collection($attachments, $transformer);
    }
}
