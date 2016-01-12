<?php

namespace App\Transformers;

use App\Tag;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class TagTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['articles'];

    /**
     * Transform single resource.
     *
     * @param  \App\Tag $tag
     * @return  array
     */
    public function transform(Tag $tag)
    {
        return [
            'id'       => optimus((int) $tag->id),
            'slug'     => $tag->slug,
            'created'  => $tag->created_at->toIso8601String(),
            'link'     => [
                'rel'  => 'self',
                'href' => route('api.v1.tags.articles.index', $tag->slug),
            ],
            'articles' => (int) $tag->articles->count(),
        ];
    }

    /**
     * Include articles.
     *
     * @param  \App\Tag                      $tag
     * @param  \League\Fractal\ParamBag|null $params
     * @return  \League\Fractal\Resource\Collection
     * @throws  \Exception
     */
    public function includeArticles(Tag $tag, ParamBag $params = null)
    {
        list($limit, $offset, $orderCol, $orderBy) = $this->calculateParams($params);

        $articles = $tag->articles()->limit($limit)->offset($offset)->orderBy($orderCol, $orderBy)->get();

        return $this->collection($articles, new \App\Transformers\ArticleTransformer);
    }
}
