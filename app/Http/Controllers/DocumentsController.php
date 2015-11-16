<?php

namespace App\Http\Controllers;

use App\Document;
use Cache;
use Image;
use Request;

class DocumentsController extends Controller
{

    /**
     * @var \App\Document
     */
    protected $document;

    /**
     * Constructor.
     *
     * @param \App\Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Show document page in response to the given $file.
     *
     * @param string $file
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($file = '01-welcome.md')
    {
        $index = Cache::remember('documents.index', 120, function () {
            return markdown($this->document->get());
        });

        $content = Cache::remember("documents.{$file}", 120, function () use ($file) {
            return markdown($this->document->get($file));
        });

        return view('documents.index', compact('index', 'content'));
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
