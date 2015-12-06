<?php

namespace App\Http\Controllers;

use App\Document;
use App\Repositories\LessonRepository;
use Request;

class LessonsController extends Controller
{
    /**
     * @var \App\Repositories\LessonRepository
     */
    protected $repo;

    /**
     * Constructor.
     *
     * @param \App\Repositories\LessonRepository $repo
     */
    public function __construct(LessonRepository $repo)
    {
        $this->repo = $repo;

        parent::__construct();
    }

    /**
     * Show document page in response to the given $file.
     *
     * @param string $file
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($file = '01-welcome.md')
    {
        $lesson = $this->repo->find($file);

        $commentsCollection = $lesson->comments()->with('replies')
            ->withTrashed()->whereNull('parent_id')->latest()->get();

        return view('lessons.show', [
            'index'           => $this->repo->index(),
            'lesson'          => $lesson,
            'prev'            => $this->repo->prev($file),
            'next'            => $this->repo->next($file),
            'comments'        => $commentsCollection,
            'commentableType' => $this->repo->model(),
            'commentableId'   => $lesson->id,
        ]);
    }

    /**
     * Make image response.
     *
     * @param $file
     * @return \Illuminate\Http\Response
     */
    public function image($file)
    {
        $image = $this->repo->image($file);
        $reqEtag = Request::getEtags();
        $genEtag = $this->repo->etag($file);

        if (isset($reqEtag[0])) {
            if ($reqEtag[0] === $genEtag) {
                return response('', 304);
            }
        }

        return response($image->encode('png'), 200, [
            'Content-Type'  => 'image/png',
            'Cache-Control' => 'public, max-age=0',
            'Etag'          => $genEtag,
        ]);
    }

    /**
     * Leave off unnecessary string from the given path.
     *
     * @param $path
     * @return mixed
     */
    protected function sanitizePath($path)
    {
        return starts_with($path, ['/lessons/', 'lessons/'])
            ? pathinfo($path, PATHINFO_BASENAME)
            : $path;
    }
}
