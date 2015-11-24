<?php

namespace App\Http\Middleware;

use Closure;

class CanAccessArticle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        $articleId = $request->route('articles');

        if (! \App\Article::whereId($articleId)->whereAuthorId($user->id)->exists() and ! $user->isAdmin()) {
            flash()->error(trans('errors.forbidden') . ' : ' . trans('errors.forbidden_description'));

            return back();
        }

        return $next($request);
    }
}
