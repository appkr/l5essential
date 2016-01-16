<?php

namespace App\Http\Controllers\Api\V1;

use App\Article;
use App\Transformers\ArticleTransformer;
use App\Http\Controllers\ArticlesController as ParentController;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticlesController extends ParentController
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
        $this->middleware('throttle.api:60,1');
        $this->middleware('obfuscate:article');

        parent::__construct();
    }

    /**
     * Respond Article collection in JSON.
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $articles
     * @param string|null                                 $cacheKey
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCollection(LengthAwarePaginator $articles, $cacheKey = null)
    {
        $reqEtag = request()->getETags();
        $genEtag = $this->etags($articles, $cacheKey);

        if (isset($reqEtag[0]) and $reqEtag[0] === $genEtag) {
            return $this->respondNotModified();
        }

        return json()->setHeaders(['Etag' => $genEtag])->withPagination(
            $articles,
            new ArticleTransformer
        );
    }

    /**
     * Respond 201 in JSON.
     *
     * @param \App\Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated(Article $article)
    {
        return json()->created();
    }

    /**
     * Respond single Article item in JSON.
     *
     * @param \App\Article                                  $article
     * @param \Illuminate\Database\Eloquent\Collection|null $commentsCollection
     * @param string|null                                   $cacheKey
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondItem(Article $article, Collection $commentsCollection = null, $cacheKey = null)
    {
        $reqEtag = request()->getETags();
        $genEtag = $article->etag($cacheKey);

        if (isset($reqEtag[0]) and $reqEtag[0] === $genEtag) {
            return $this->respondNotModified();
        }

        return json()->setHeaders(['Etag' => $genEtag])->withItem($article, new ArticleTransformer);
    }

    /**
     * Respond Updated in JSON.
     *
     * @param \App\Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUpdated(Article $article)
    {
        return json()->success('Updated');
    }

    /**
     * Respond 204 Deleted.
     *
     * @param \App\Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondDeleted(Article $article)
    {
        return json()->noContent();
    }

    /**
     * Respond Not Modified;
     *
     * @return \Illuminate\Contracts\Http\Response
     */
    protected function respondNotModified()
    {
        return json()->notModified();
    }
}
