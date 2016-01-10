<?php

// Todo write test for Comment, Tag, Attachment.

namespace Test\Http\Controllers\Api\V1;

use Teapot\StatusCode\All as StatusCode;
use Test\Http\Controllers\Api\ApiTest;

class ArticlesController extends ApiTest
{
    /** @test */
    public function it_fetches_collection_of_articles()
    {
        $this->get(
                route('api.v1.articles.index'),
                $this->httpHeaders()
            )
            ->seeStatusCode(StatusCode::OK)
            ->seeJson();
    }

    /** @test */
    public function it_fails_fetching_collection_when_querystrings_not_valid()
    {
        $this->get(
                route('api.v1.articles.index', ['f' => 'abc']),
                $this->httpHeaders()
            )
            ->seeStatusCode(StatusCode::UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_fetches_single_article()
    {
        $this->get(
            // 1 is safe, because it's in the seeding
                route('api.v1.articles.show', 1),
                $this->httpHeaders()
            )
            ->seeStatusCode(StatusCode::OK)
            ->seeJson();
    }

    /** @test */
    public function it_fails_fetching_article_when_model_not_exist()
    {
        $this->get(
                route('api.v1.articles.show', 10000),
                $this->httpHeaders()
            )
            ->seeStatusCode(StatusCode::NOT_FOUND)
            ->seeJson();
    }

    /** @test */
    public function it_creates_new_article()
    {

        $this->createUserStub()
            ->login()
            ->post(
                route('api.v1.articles.store'),
                [
                    'title' => 'foo',
                    'content' => 'bar',
                    'tags' => [1]
                ],
                $this->httpHeaders($this->jwtHeader())
            )
            ->seeStatusCode(StatusCode::CREATED)
            ->seeJson()
            ->seeInDatabase('articles', ['title' => 'foo']);
    }

    /** @test */
    public function it_fails_creating_new_article_if_given_jwt_token_is_not_valid()
    {
        $this->createUserStub()
            ->login()
            ->post(
                route('api.v1.articles.store'),
                [
                    'title' => 'foo',
                    'content' => 'bar',
                    'tags' => [1]
                ],
                $this->httpHeaders($this->jwtHeader('malformed.jwt.token'))
            )
            ->seeStatusCode(StatusCode::BAD_REQUEST)
            ->seeJsonContains(['message' => 'token_invalid']);
    }

    /** @test */
    public function it_fails_creating_new_article_if_request_payload_not_valid()
    {
        $this->createUserStub()
            ->login()
            ->post(
                route('api.v1.articles.store'),
                ['title' => 'foo',],
                $this->httpHeaders($this->jwtHeader())
            )
            ->seeStatusCode(StatusCode::UNPROCESSABLE_ENTITY)
            ->seeJson();
    }

    /** @test */
    public function it_updates_article()
    {
        $this->createUserStub()
            ->login()
            ->createArticleStub(['title' => 'foo'])
            ->put(
                route('api.v1.articles.update', $this->article->id),
                ['title' => 'bar',],
                $this->httpHeaders($this->jwtHeader())
            )
            ->seeStatusCode(StatusCode::OK)
            ->seeJson()
            ->seeInDatabase('articles', ['title' => 'bar']);
    }

    /** @test */
    public function it_fails_updating_article_when_not_owner()
    {
        $this->createUserStub()
            ->login()
            ->put(
                // Assumes that article 1 exists by seeding.
                route('api.v1.articles.update', 1),
                ['title' => 'bar',],
                $this->httpHeaders($this->jwtHeader())
            )
            ->seeStatusCode(StatusCode::FORBIDDEN)
            ->seeJson();
    }

    /** @test */
    public function it_update_best_solution()
    {
        // Todo
    }

    /** @test */
    public function it_fails_update_best_when_request_payload_not_valid()
    {
        // Todo
    }

    /** @test */
    public function it_deletes_article()
    {
        $this->createUserStub()
            ->login()
            ->createArticleStub(['title' => 'foo'])
            ->delete(
                route('api.v1.articles.destroy', $this->article->id),
                [],
                $this->httpHeaders($this->jwtHeader())
            )
            ->seeStatusCode(StatusCode::NO_CONTENT);
    }
}