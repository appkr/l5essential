<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Requests\ArticlesRequest;
use App\Http\Requests;
use App\Tag;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
        $this->middleware('author:article', ['except' => ['index', 'show', 'create']]);

        view()->share('allTags', Tag::with('articles')->get());

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param int|null $id
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        $query = $id
            ? Tag::findOrFail($id)->articles()
            : new Article;

        $articles = $query->with('comments', 'author', 'tags')->latest()->paginate(10);

        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $article = new Article;

        return view('articles.create', compact('article'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ArticlesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticlesRequest $request)
    {
        $payload = array_merge($request->except('_token'), [
            'notification' => $request->has('notification')
        ]);

        $article = $request->user()->articles()->create($payload);
        $article->tags()->sync($request->input('tags'));

        if ($request->has('attachments')) {
            $attachments = \App\Attachment::whereIn('id', $request->input('attachments'))->get();
            $attachments->each(function($attachment) use($article) {
                $attachment->article()->associate($article);
                $attachment->save();
            });
        }

        flash()->success(trans('forum.created'));

        return redirect(route('articles.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::with('comments', 'author', 'tags')->findOrFail($id);
        $commentsCollection = $article->comments()->with('replies', 'author')->whereNull('parent_id')->latest()->get();

        return view('articles.show', [
            'article'         => $article,
            'comments'        => $commentsCollection,
            'commentableType' => Article::class,
            'commentableId'   => $article->id
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);

        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ArticlesRequest $request
     * @param  int                               $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticlesRequest $request, $id)
    {
        $payload = array_merge($request->except('_token'), [
            'notification' => $request->has('notification')
        ]);

        $article = Article::findOrFail($id);
        $article->update($payload);
        $article->tags()->sync($request->input('tags'));
        flash()->success(trans('forum.updated'));

        return redirect(route('articles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::with('attachments', 'comments')->findOrFail($id);

        foreach($article->attachments as $attachment) {
            \File::delete(attachment_path($attachment->name));
        }

        $article->attachments()->delete();
        $article->comments->each(function($comment) {
            app(\App\Http\Controllers\CommentsController::class)->recursiveDestroy($comment);
        });
        $article->delete();

        flash()->success(trans('forum.deleted'));

        return redirect(route('articles.index'));
    }
}
