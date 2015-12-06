<?php

namespace App\Http\Controllers;

use App\Article;
use App\Events\ArticleConsumed;
use App\Events\ModelChanged;
use App\Http\Requests\ArticlesRequest;
use App\Http\Requests\FilterArticlesRequest;
use App\Tag;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
        $this->middleware('author:article', ['only' => ['update', 'destroy', 'pickBest']]);

        $allTags = taggable()
            ? Tag::with('articles')->remember(5)->cacheTags('tags')->get()
            : Tag::with('articles')->remember(5)->get();

        view()->share('allTags', $allTags);

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\FilterArticlesRequest $request
     * @param string|null                              $slug
     * @return \Illuminate\Http\Response
     */
    public function index(FilterArticlesRequest $request, $slug = null)
    {
        $query = $slug
            ? Tag::whereSlug($slug)->firstOrFail()->articles()
            : new Article;

        // If you are relying on 'file' or 'database' cache, cacheTags() methods is not available
        $query = taggable()
            ? $query->with('comments', 'author', 'tags', 'attachments')->remember(5)->cacheTags('articles')
            : $query->with('comments', 'author', 'tags', 'solution', 'attachments')->remember(5);

        $articles = $this->filter($request, $query)->paginate(10);

        return view('articles.index', compact('articles'));
    }

    /**
     * Do the filter, search, and sorting job
     *
     * @param $request
     * @param $query
     * @return mixed
     */
    protected function filter($request, $query)
    {
        if ($filter = $request->input('f')) {
            switch ($filter) {
                case 'nocomment':
                    $query->noComment();
                    break;
                case 'notsolved':
                    $query->notSolved();
                    break;
            }
        }

        if ($keyword = $request->input('q')) {
            $raw = 'MATCH(title,content) AGAINST(? IN BOOLEAN MODE)';
            $query->whereRaw($raw, [$keyword]);
        }

        $sort = $request->input('s', 'created_at');
        $direction = $request->input('d', 'desc');

        return $query->orderBy('pin', 'desc')->orderBy($sort, $direction);
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
            'notification' => $request->has('notification'),
        ]);

        $article = $request->user()->articles()->create($payload);
        $article->tags()->sync($request->input('tags'));

        if ($request->has('attachments')) {
            $attachments = \App\Attachment::whereIn('id', $request->input('attachments'))->get();
            $attachments->each(function ($attachment) use ($article) {
                $attachment->article()->associate($article);
                $attachment->save();
            });
        }

        event(new ModelChanged(['articles', 'tags']));
        flash()->success(trans('common.created'));

        return redirect(route('articles.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::with('comments', 'tags', 'attachments', 'solution')->findOrFail($id);
        $commentsCollection = $article->comments()->with('replies')
            ->withTrashed()->whereNull('parent_id')->latest()->get();

        event(new ArticleConsumed($article));

        return view('articles.show', [
            'article'         => $article,
            'comments'        => $commentsCollection,
            'commentableType' => Article::class,
            'commentableId'   => $article->id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
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
            'notification' => $request->has('notification'),
        ]);

        $article = Article::findOrFail($id);
        $article->update($payload);
        $article->tags()->sync($request->input('tags'));

        event(new ModelChanged(['articles', 'tags']));
        flash()->success(trans('common.updated'));

        return redirect(route('articles.show', $id));
    }

    public function pickBest(Request $request, $id)
    {
        $this->validate($request, [
            'solution_id' => 'required|numeric|exists:comments,id',
        ]);

        Article::findOrFail($id)->update([
            'solution_id' => $request->input('solution_id'),
        ]);


        return response()->json('', 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int                     $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        $article = Article::with('attachments', 'comments')->findOrFail($id);

        foreach ($article->attachments as $attachment) {
            \File::delete(attachment_path($attachment->name));
        }

        $article->attachments()->delete();
        $article->comments->each(function ($comment) use ($request) {
            app(\App\Http\Controllers\CommentsController::class)->destroy($request, $comment->id);
        });
        $article->delete();

        event(new ModelChanged('articles'));

        if ($request->ajax()) {
            return response()->json('', 204);
        }

        flash()->success(trans('common.deleted'));

        return redirect(route('articles.index'));
    }
}
