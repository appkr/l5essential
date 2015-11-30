<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('author:comment', ['except' => ['store']]);
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
            'commentable_type' => 'required|in:App\Article',
            'commentable_id'   => 'required|numeric',
            'parent_id'        => 'numeric|exists:comments,id',
            'content'          => 'required',
        ]);

        $parentModel = "\\" . $request->input('commentable_type');
        $parentModel::find($request->input('commentable_id'))
            ->comments()->create([
                'author_id' => \Auth::user()->id,
                'parent_id' => $request->input('parent_id', null),
                'content'   => $request->input('content')
            ]);

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

        Comment::findOrFail($id)->update($request->only('content'));
        flash()->success(trans('forum.comment_edit'));

        return back();
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
        $comment = Comment::find($id);
        $this->recursiveDestroy($comment);

        if ($request->ajax()) {
            return response()->json('', 204);
        }

        flash()->success(trans('forum.deleted'));

        return back();
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
