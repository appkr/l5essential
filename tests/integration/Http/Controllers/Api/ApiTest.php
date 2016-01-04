<?php

namespace Test\Http\Controllers\Api;

use App\Article;
use App\Attachment;
use App\Comment;
use App\User;
use Bican\Roles\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiTest extends \TestCase
{
    use DatabaseTransactions;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Article
     */
    protected $article;

    /**
     * @var array
     */
    protected $userPayload = [
        'name'     => 'foo',
        'email'    => 'foo@bar.com',
        'password' => 'password',
    ];

    /**
     * @var string
     */
    protected $jwtToken;

    /**
     * Set up.
     */
    public function setUp()
    {
        parent::setUp();
        $this->baseUrl = sprintf('http://' . env('API_DOMAIN'));
    }

    /**
     * Mimic the login attempt.
     *
     * @param array $overrides
     * @param array $headers
     * @return $this
     */
    public function login($overrides = [], $headers = [])
    {
        return $this->post(
            route('api.sessions.store'),
            array_merge([
                'email'    => $this->user->email,
                'password' => 'password',
            ], $overrides),
            $this->httpHeaders($headers)
        )->parseJwtToken();
    }

    /**
     * Mimic the user registration attempt.
     *
     * @param array $overrides
     * @param array $headers
     * @return $this
     */
    public function register($overrides = [], $headers = [])
    {
        return $this->post(
            route('api.users.store'),
            array_merge(
                $this->userPayload,
                ['password_confirmation' => $this->userPayload['password']],
                $overrides
            ),
            $this->httpHeaders($headers)
        )->parseJwtToken();
    }

    /**
     * Mimic the password remind attempt.
     *
     * @param array $overrides
     * @param array $headers
     * @return $this
     */
    public function remind($overrides = [], $headers = [])
    {
        return $this->post(
            route('api.remind.store'),
            [
                'email' => array_key_exists('email', $overrides)
                    ? $overrides['email']
                    : $this->user->email
            ],
            $this->httpHeaders($headers)
        );
    }

    /**
     * Stubbing test data.
     *
     * @param array $overrides
     */
    protected function createTestStub($overrides = [])
    {
        $this->user = empty($overrides)
            ? factory(User::class)->create()
            : factory(User::class)->create($overrides);

        $this->user->attachRole(Role::find(2));

        $this->article = factory(Article::class)->create([
            'title'     => 'title',
            'author_id' => $this->user->id,
            'content'   => 'description',
        ]);

        $this->article->comments()->save(
            factory(Comment::class)->make([
                'author_id' => $this->user->id
            ])
        );

        $this->article->tags()->attach(1);

        $this->article->attachments()->save(
            factory(Attachment::class)->make()
        );
    }

    /**
     * Set or Get http request header.
     *
     * @param array $append
     *
     * @return array
     */
    protected function httpHeaders($append = [])
    {
        return array_merge([
            'Accept' => 'application/json'
        ], $append);
    }

    /**
     * Parse jwt token out of the login or register request.
     *
     * @return $this
     */
    protected function parseJwtToken()
    {
        $json = json_decode($this->response->getContent());

        if (isset($json->meta->token)) {
            $this->jwtToken = $json->meta->token;
        }

        return $this;
    }

    /**
     * Build Authorization header with jwt token.
     *
     * @return array
     */
    protected function jwtHeader()
    {
        return ['HTTP_Authorization' => 'Bearer ' . $this->jwtToken];
    }
}