<?php

namespace Test\Http\Controllers\Api\V1;

use Test\Http\Controllers\Api\ApiTest;

class ArticlesController extends ApiTest
{
    /** @test */
    public function it_fetches_collection_of_articles()
    {
        $this->register()
            ->get(
                route('api.v1.articles.index'),
                $this->httpHeaders($this->jwtHeader())
            )
            ->seeStatusCode(200)
            ->seeJson();
    }

    /** @test */
    public function it_fails_fetching_collection_when_querystrings_not_valid()
    {

    }

    /** @test */
    public function it_fetches_single_article()
    {

    }

    /** @test */
    public function it_fails_fetching_article_when_model_not_exist()
    {

    }

    /** @test */
    public function it_creates_new_article()
    {
        // test tags
        // test attachment
    }

    /** @test */
    public function it_fails_creating_new_article_if_request_payload_not_valid()
    {

    }

    /** @test */
    public function it_updates_article()
    {
        // test tags
        // test attachment
    }

    /** @test */
    public function it_fails_updating_article_when_not_owner()
    {

    }

    /** @test */
    public function it_fails_updating_article_if_request_payload_not_valid()
    {

    }

    /** @test */
    public function it_update_best_solution()
    {

    }

    /** @test */
    public function it_fails_update_best_when_request_payload_not_valid()
    {

    }

    /** @test */
    public function it_deletes_article()
    {
        // test comment
        // test attachment
    }

    /** @test */
    public function it_fails_deleting_article_when_not_owner()
    {

    }
}