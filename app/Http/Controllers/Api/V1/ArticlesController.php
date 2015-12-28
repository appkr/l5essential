<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return \App\Article::all();
    }
}
