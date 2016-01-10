<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthorOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $param)
    {
        $user = $request->user();
        $model = '\\App\\' . ucfirst($param);
        $modelId = $request->route($param == 'id' ? 'id' : str_plural($param));

        if (! $model::whereId($modelId)->whereAuthorId($user->id)->exists() and ! $user->isAdmin()) {
            if (is_api_request()) {
                return json()->forbiddenError();
            }

            flash()->error(trans('errors.forbidden') . ' : ' . trans('errors.forbidden_description'));

            return back();
        }

        return $next($request);
    }
}
