<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Debug
    |--------------------------------------------------------------------------
    |
    | If set to true, debug information will be include in api response.
    | Must set to false for production.
    |
    */
    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | API Endpoint pattern
    |--------------------------------------------------------------------------
    |
    | Path 'pattern' used for is_api_request() Helper.
    | Provide 'domain', if the api routes are distinguished by domain name.
    |
    */
    'endpoint' => [
        'pattern' => 'v1/*',
        'domain'  => env('API_DOMAIN', 'api.myproject.dev'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Include by query string.
    |--------------------------------------------------------------------------
    |
    | If you defined 'availableInclude' property and includeXxx methods
    | in a transformer, you can include sub resources using query string.
    | e.g. /authors?include=books:limit(3|0):order(id|desc) means
    | including 3 records of 'authors', which is reverse ordered by 'id' field,
    | without any skipping(0).
    |
    | An API client can pass list of includes using array or csv string format.
    | e.g. /authors?include[]=books:limit(2|0)&include[]=comments:order(id|asc)
    |      /authors?include=books:limit(2|0),comments:order(id|asc)
    |
    | For sub-resource inclusion, client can use dot(.) notation.
    | e.g. /books?include=author,publisher.somethingelse
    |
    */
    'include' => [
        'key' => 'include',
        'params' => [ // available modifier params and their default value
            'limit' => [3, 0], // [limit, offset]
            'sort' => ['created_at', 'desc'], // [sortKey, sortDirection]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Partial response
    |--------------------------------------------------------------------------
    |
    | API clients are allowed to select the response format using query string.
    | This will help saving network bandwidth..
    | e.g. /author?fields=id,title,link&include=books:fields(id|title|published_at)
    |
    */
    'partial' => [
        'key' => 'fields',
    ],

    /*
    |--------------------------------------------------------------------------
    | Transformer directory and namespace.
    |--------------------------------------------------------------------------
    |
    | Below config will be applied when we run 'make:transformer' artisan cmd.
    | The generated class will be saved at 'dir', and namespaced as you set.
    | Note that the 'dir' should be relative to the project root.
    |
    */
    'transformer' => [
        'dir' => 'app/Transformers',
        'namespace' => 'App\\Transformers',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fractal Serializer
    |--------------------------------------------------------------------------
    |
    | Refer to
    | http://fractal.thephpleague.com/serializers/
    |
    */
    'serializer' => \League\Fractal\Serializer\ArraySerializer::class,

    /*
    |--------------------------------------------------------------------------
    | Default Response Headers
    |--------------------------------------------------------------------------
    |
    | Default response headers that every resource/simple response should includes
    |
    */
    'defaultHeaders' => ['X-Powered-By' => 'appkr/api'],

    /*
    |--------------------------------------------------------------------------
    | Suppress HTTP status code
    |--------------------------------------------------------------------------
    |
    | If set to true, the status code will be fixed to 200.
    |
    */
    'suppress_response_code' => false,

    /*
    |--------------------------------------------------------------------------
    | Success Response Format
    |--------------------------------------------------------------------------
    |
    | The format will be used at the ApiResponse to respond with success message.
    | respondNoContent(), respondSuccess(), respondCreated() consumes this format
    |
    */
    'successFormat' => [
        'success' => [
            'code'    => ':code',
            'message' => ':message',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Response Format
    |--------------------------------------------------------------------------
    |
    | The format will be used at the ApiResponse to respond with error message.
    | respondWithError(), respondForbidden()... consumes this format
    |
    */
    'errorFormat' =>  [
        'error' => [
            'code'    => ':code',
            'message' => ':message',
        ]
    ]

];
