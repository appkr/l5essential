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

        return [
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
            'author'       => sprintf('%s <%s>', $article->author->name, $article->author->email),
            'tags'         => $article->tags->pluck('slug'),
            'attachments'  => (int) $article->attachments->count(),
        ];
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
        list($limit, $offset, $orderCol, $orderBy) = $this->calculateParams($params);

        $comments = $article->comments()->limit($limit)->offset($offset)->orderBy($orderCol, $orderBy)->get();

        return $this->collection($comments, new \App\Transformers\CommentTransformer);
    }

    /**
     * Include author.
     *
     * @param  \App\Article $article
     * @return  \League\Fractal\Resource\Item
     */
    public function includeAuthor(Article $article)
    {
        return $this->item($article->author, new \App\Transformers\UserTransformer);
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
        list($limit, $offset, $orderCol, $orderBy) = $this->calculateParams($params);

        $tags = $article->tags()->limit($limit)->offset($offset)->orderBy($orderCol, $orderBy)->get();

        return $this->collection($tags, new \App\Transformers\TagTransformer);
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
        list($limit, $offset, $orderCol, $orderBy) = $this->calculateParams($params);

        $attachments = $article->attachments()->limit($limit)->offset($offset)->orderBy($orderCol, $orderBy)->get();

        return $this->collection($attachments, new \App\Transformers\AttachmentTransformer);
    }
}
