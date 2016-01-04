<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Transformers\CommentTransformer;

class CommentsController extends Controller
{
    public function __construct()
    {
//        $this->middleware('jwt.auth');

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return json()->withPagination(
            \App\Comment::paginate(5),
            new CommentTransformer
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $article = \App\Comment::findOrFail($id);

        return json()->withItem(
            $article,
            new CommentTransformer
        );
    }
}
