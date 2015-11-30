<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class AttachmentsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! $request->hasFile('file')) {
            return response()->json('File not passed !', 422);
        }

        // Save file
        $file = $request->file('file');
        $name = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $file->move(attachment_path(), $name);

        $articleId = $request->input('articleId');

        // Persist Attachment model
        $attachment = $articleId
            ? \App\Article::findOrFail($articleId)->attachments()->create(['name' => $name])
            : \App\Attachment::create(['name' => $name]);

        return response()->json([
            'id'   => $attachment->id,
            'name' => $name,
            'type' => $file->getClientMimeType(),
            'url'  => sprintf("/attachments/%s", $name),
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
        $attachment = \App\Attachment::findOrFail($id);

        $path = attachment_path($attachment->name);
        if (\File::exists($path)) {
            \File::delete($path);
        }

        $attachment->delete();

        if ($request->ajax()) {
            return response()->json('', 204);
        }

        flash()->success(trans('forum.deleted'));

        return back();
    }
}
