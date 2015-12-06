<?php

namespace App\Http\Controllers;

use App\Document;
use App\DocumentRepository;
use Cache;
use Request;

class DocumentsController extends Controller
{

    /**
     * @var \App\DocumentRepository
     */
    protected $document;

    /**
     * Constructor.
     *
     * @param \App\DocumentRepository $repo
     */
    public function __construct(DocumentRepository $repo)
    {
        parent::__construct();

        $this->document = $repo;
    }

    /**
     * Show document page in response to the given $file.
     *
     * @param string $file
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($file = '01-welcome.md')
    {
        // Index does not change frequently.
        $index = Cache::remember('documents.index', 120, function () {
            return $this->document->index();
        });

        $document = $this->document->find($file);

        $commentsCollection = $document->comments()->with('replies')
            ->withTrashed()->whereNull('parent_id')->latest()->get();

        return view('documents.show', [
            'index'           => $index,
            'document'        => $document,
            'comments'        => $commentsCollection,
            'commentableType' => Document::class,
            'commentableId'   => $document->id,
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
        $image = $this->document->image($file);
        $reqEtag = Request::getEtags();
        $genEtag = $this->document->etag($file);

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

}
