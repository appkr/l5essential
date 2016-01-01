<?php

namespace Test\Http\Controllers;

use App\Article;
use App\Attachment;
use App\Comment;
use App\User;
use Bican\Roles\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends \TestCase
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
        'name' => 'foo',
        'email' => 'foo@bar.com',
        'password' => 'password',
    ];

    /**
     * Set up.
     */
    public function setUp()
    {
        parent::setUp();
        $this->baseUrl = sprintf('http://' . env('APP_DOMAIN'));
    }

    /**
     * Visit login page and attempt login.
     *
     * @param array $overrides
     * @return mixed
     */
    public function login($overrides = [])
    {
        return $this->visit(route('sessions.create'))
            ->submitForm(
                trans('auth.button_login'),
                array_merge([
                    'email' => $this->user->email,
                    'password' => 'password',
                ], $overrides)
            );
    }

    /**
     * Visit login route.
     *
     * @return mixed
     */
    public function logout()
    {
        return $this->visit(route('sessions.destroy'));
    }

    /**
     * Visit signup page and attempt user registration.
     *
     * @param array $overrides
     * @return $this
     */
    public function register($overrides = [])
    {
        return $this->visit(route('users.create'))
            ->submitForm(
                trans('auth.button_signup'),
                array_merge(
                    $this->userPayload,
                    ['password_confirmation' => $this->userPayload['password']],
                    $overrides
                )
            );
    }

    /**
     * Visit password remind page and attempt the password remind.
     *
     * @param array $overrides
     * @return $this
     */
    public function remind($overrides = [])
    {
        return $this->visit(route('remind.create'))
            ->submitForm(
                trans('auth.button_send_reminder'),
                [
                    'email' => array_key_exists('email', $overrides)
                        ? $overrides['email']
                        : $this->user->email
                ]
            );
    }

    /**
     * Stubbing test data.
     *
     * @param array $overrides
     */
    protected function createTestStub($overrides = [])
    {
        $this->user = ! empty($overrides)
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

    /** @test */
    public function it_is_dummy() {}
}