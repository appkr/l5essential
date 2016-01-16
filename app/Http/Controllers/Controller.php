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
     * Constructor
     */
    public function __construct() {
        if (! is_api_request()) {
            $this->setSharedVariables();
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

    public function etags($collection, $cacheKey = null)
    {
        $etag = '';

        foreach($collection as $instance) {
            $etag .= $instance->etag();
        }

        return md5($etag.$cacheKey);
    }
}
