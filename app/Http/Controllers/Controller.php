<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * Constructor
     */
    public function __construct() {
        if (! is_api_request()) {
            $this->setSharedVariables();
        }

        $this->cache = app('cache');

        if ((new \ReflectionClass($this))->implementsInterface(\App\Http\Controllers\Cacheable::class) and taggable()) {
            $this->cache = app('cache')->tags($this->cacheKeys());
        }
    }

    /**
     * Share common view variables
     */
    protected function setSharedVariables() {
        view()->share('currentLocale', app()->getLocale());
        view()->share('currentUser', auth()->user());
        view()->share('currentRouteName', \Route::currentRouteName());
        view()->share('currentUrl', current_url());
        view()->share('isLandingPage', in_array(\Route::currentRouteName(), ['home', 'index']));
    }

    /**
     * Do the filter, search, and sorting job
     *
     * @param $query
     * @return mixed
     */
    protected function filter($query)
    {
        $params = config('project.params');

        if ($filter = request()->input($params['filter'])) {
            $query = $query->{camel_case($filter)}();
        }

        if ($keyword = request()->input($params['search'])) {
            $raw = 'MATCH(title,content) AGAINST(? IN BOOLEAN MODE)';
            $query = $query->whereRaw($raw, [$keyword]);
        }

        $sort = request()->input($params['sort'], 'created_at');
        $direction = request()->input($params['order'], 'desc');

        if ($sort == 'created') {
            // We transformed field name of 'created_at' to 'created'.
            // Applicable only to api request. But this code laid here
            // to suppress QueryException of not existing column in web request.
            $sort = 'created_at';
        }

        return $query->orderBy($sort, $direction);
    }

    /**
     * Create etag against collection of resources.
     *
     * @param \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator| $collection
     * @param string|null $cacheKey
     * @return string
     */
    protected function etags($collection, $cacheKey = null)
    {
        $etag = '';

        foreach($collection as $instance) {
            $etag .= $instance->etag();
        }

        return md5($etag.$cacheKey);
    }

    /**
     * Execute caching against database query.
     *
     * @see config/project.php's cache section.
     *
     * @param string $key
     * @param int $minutes
     * @param \App\Model|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     *        |\Illuminate\Database\Eloquent\Relations\Relation $query
     * @param string $method
     * @param mixed ...$args
     * @return mixed
     */
    protected function cache($key, $minutes, $query, $method, ...$args)
    {
        $args = (! empty($args)) ? implode(',', $args) : null;

        if (config('project.cache') === false) {
            return $query->{$method}($args);
        }

        return $this->cache->remember($key, $minutes, function() use($query, $method, $args){
            return $query->{$method}($args);
        });
    }
}
