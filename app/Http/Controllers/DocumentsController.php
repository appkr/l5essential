<?php

namespace App\Http\Controllers;

use App\Document;
use App\Http\Requests;
use Cache;
use Image;

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
     * Show document page in response to the given $file
     *
     * @param string $file
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($file = '01-welcome.md')
    {
        $index = Cache::remember('documents.index', 120, function () {
            return markdown($this->document->get());
        });

        $content = Cache::remember("documents.{$file}", 120, function() use ($file) {
            return markdown($this->document->get($file));
        });

        return view('documents.index', compact('index', 'content'));
    }

    /**
     * Make image response
     *
     * @param $file
     * @return \Illuminate\Http\Response
     */
    public function image($file) {
        $image = Image::make($this->document->imagePath($file));

        return $image->response('png');
    }

}
