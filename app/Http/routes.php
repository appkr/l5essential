<?php

Route::post('posts', function(\Illuminate\Http\Request $request) {
    $rule = [
        'title' => ['required'],
        'body' => ['required', 'min:10']
    ];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
        return redirect('posts/create')->withErrors($validator)->withInput();
    }

    return 'Valid & proceed to next job ~';
});

Route::get('posts/create', function() {
    return view('posts.create');
});

//Route::resource('posts', 'PostsController');