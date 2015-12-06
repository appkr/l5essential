<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Vote;
use App\Events\ModelChanged;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('author:comment', ['except' => ['store', 'vote']]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'commentable_type' => 'required|in:App\Article,App\Lesson',
            'commentable_id'   => 'required|numeric',
            'parent_id'        => 'numeric|exists:comments,id',
            'content'          => 'required',
        ]);

        $parentModel = "\\" . $request->input('commentable_type');
        $comment = $parentModel::find($request->input('commentable_id'))
            ->comments()->create([
                'author_id' => \Auth::user()->id,
                'parent_id' => $request->input('parent_id', null),
                'content'   => $request->input('content')
            ]);

        event('comments.created', [$comment]);
        event(new ModelChanged('comments'));
        flash()->success(trans('forum.comment_add'));

        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['content' => 'required']);

        $comment = Comment::findOrFail($id);
        $comment->update($request->only('content'));

        event('comments.updated', [$comment]);
        event(new ModelChanged('comments'));
        flash()->success(trans('forum.comment_edit'));

        return back();
    }

    /**
     * Vote up or down for the given comment.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(Request $request, $id)
    {
        $this->validate($request, [
            'vote' => 'required|in:up,down',
        ]);

        if(Vote::whereCommentId($id)->whereUserId($request->user()->id)->exists()) {
            return response()->json(['errors' => 'Already voted!'], 409);
        }

        $comment = Comment::findOrFail($id);

        $up = $request->input('vote') == 'up' ? true : false;

        $comment->votes()->create([
            'user_id'  => $request->user()->id,
            'up'       => $up ? 1 : null,
            'down'     => $up ? null : 1,
            'voted_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        return response()->json([
            'voted' => $request->input('vote'),
            'value' => $comment->votes()->sum($request->input('vote'))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int                     $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $comment = Comment::with('replies')->find($id);

        // Do not recursively destroy children comments.
        // Because 1. Soft delete feature was adopted,
        // and 2. it's not just pleasant for authors of children comments to being deleted by the parent author.
        if ($comment->replies->count() > 0) {
            $comment->delete();
        } else {
            if ($comment->votes->count()) {
                $this->deleteVote($comment->votes);
            }

            $comment->forceDelete();
        }

        // $this->recursiveDestroy($comment);

        event(new ModelChanged('comments'));

        if ($request->ajax()) {
            return response()->json('', 204);
        }

        flash()->success(trans('common.deleted'));

        return back();
    }

    /**
     * Delete given votes collection.
     *
     * @param $votes
     */
    protected function deleteVote($votes)
    {
        foreach($votes as $vote) {
            $vote->delete();
        }
    }

    /**
     * Delete comment recursively
     *
     * @param \App\Comment $comment
     * @return bool|null
     */
    public function recursiveDestroy(Comment $comment)
    {
        if ($comment->replies->count()) {
            $comment->replies->each(function($reply) {
                if ($reply->replies->count()) {
                    $this->recursiveDestroy($reply);
                } else {
                    $reply->delete();
                }
            });
        }

        return $comment->delete();
    }
}
