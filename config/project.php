<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Project identification
    |--------------------------------------------------------------------------
    |
    */
    'name'        => 'l5essential',
    'description' => 'Laravel 5 입문 및 실전 강좌',

    /*
    |--------------------------------------------------------------------------
    | People & contacts
    |--------------------------------------------------------------------------
    |
    */
    'contacts'    => [
        'author' => [
            'name'         => 'appkr',
            'email'        => 'juwonkim@me.com',
            'url'          => '',
            'organization' => 'Appkr Studio',
        ],
        'admin'  => [
            // ...
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO keys
    |--------------------------------------------------------------------------
    |
    | @see https://www.google.com/webmasters/tools/home
    |      Note. Is not required when Google Analytics is activated.
    | @see http://webmastertool.naver.com/board/main.naver
    |
    */
    'seo'         => [
        'google_site_key' => 'ToXKBimREnz49pDNot4b-N9ZJgYcKXPPsHsjhg4Zzuc',
        'naver_site_key'  => '7cebcc8e5493169f5401870d9ce57f48d18491cd',
    ],

    /*
    |--------------------------------------------------------------------------
    | Available/allowed fields for query string
    |--------------------------------------------------------------------------
    |
    */
    'params'      => [
        'page'   => 'page',
        'filter' => 'filter',
        'limit'  => 'limit',
        'sort'   => 'sort',
        'order'  => 'order',
        'search' => 'q',
        'select' => 'fields',
    ],

    /*
    |--------------------------------------------------------------------------
    | Available/allowed value for 'filter' query string
    |--------------------------------------------------------------------------
    |
    | 'model_in_lower_case' => ['filter_key' => 'description'],
    | filter_key will be transformed to camelCase when calling the scope query.
    |     e.g. no_comment -> noComment
    |
    */
    'filters' => [
        'article' => [
            'no_comment' => 'No Comment',
            'not_solved' => 'Not Solved'
        ]
    ],

];