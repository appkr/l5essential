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
        $payload = [
            'id'       => optimus((int) $tag->id),
            'slug'     => $tag->slug,
            'created'  => $tag->created_at->toIso8601String(),
            'link'     => [
                'rel'  => 'self',
                'href' => route('api.v1.tags.articles.index', $tag->slug),
            ],
            'articles' => (int) $tag->articles->count(),
        ];

        if ($fields = $this->getPartialFields()) {
            $payload = array_only($payload, $fields);
        }

        return $payload;
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
        $transformer = new \App\Transformers\ArticleTransformer($params);

        $parsed = $this->getParsedParams();

        $articles = $tag->articles()->limit($parsed['limit'])->offset($parsed['offset'])->orderBy($parsed['sort'], $parsed['order'])->get();

        return $this->collection($articles, $transformer);
    }
}
