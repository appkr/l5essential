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
        $this->baseUrl = 'http://' . config('project.api_domain');
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
     * Create User.
     *
     * @param array $overrides
     * @return $this
     */
    protected function createUserStub($overrides = [])
    {
        $this->user = empty($overrides)
            ? factory(User::class)->create()
            : factory(User::class)->create($overrides);

        $this->user->attachRole(Role::find(2));

        return $this;
    }

    /**
     * Create Article.
     *
     * @param array $overrides
     * @return $this
     */
    protected function createArticleStub($overrides = [])
    {
        $payload = array_merge([
            'title'     => 'foo',
            // user 1 is safe, because we already seeded.
            'author_id' => is_null($this->user) ? 1 : $this->user->id,
            'content'   => 'bar',
        ], $overrides);

        $this->article = factory(Article::class)->create($payload);

        return $this;
    }

    /**
     * Create Comment.
     *
     * @param array $overrides
     * @return $this
     */
    protected function createCommentStub($overrides = [])
    {
        $article = is_null($this->article) ? $this->createArticleStub() : $this->article;

        $article->comments()->save(
            factory(Comment::class)->make([
                'author_id' => is_null($this->user) ? 1 : $this->user->id,
            ])
        );

        return $this;
    }

    /**
     * Create Tag.
     *
     * @return $this
     */
    protected function createTagStub()
    {
        $article = is_null($this->article) ? $this->createArticleStub() : $this->article;
        $article->tags()->attach(1);

        return $this;
    }

    /**
     * Create Attachment.
     *
     * @return $this
     */
    protected function createAttachmentStub()
    {
        $article = is_null($this->article) ? $this->createArticleStub() : $this->article;
        $article->attachments()->save(factory(Attachment::class)->make());

        return $this;
    }

    /**
     * Stubbing all test data.
     *
     * @param array $userOverrides
     * @param array $articleOverrides
     * @param array $commentOverrides
     * @return $this
     */
    protected function createTestStub($userOverrides = [], $articleOverrides = [], $commentOverrides = [])
    {
        return $this->createUserStub($userOverrides)
            ->createArticleStub($articleOverrides)
            ->createCommentStub($commentOverrides)
            ->createTagStub()
            ->createAttachmentStub();
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
     * @param null $overrides
     * @return array
     */
    protected function jwtHeader($overrides = null)
    {
        $jwtToken = $overrides ?: $this->jwtToken;
        return ['HTTP_Authorization' => 'Bearer ' . $jwtToken];
    }
}