<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author_id')->unsigned()->index();
            $table->string('title');
            $table->text('content');
            $table->integer('solution_id')->unsigned()->nullable();
            $table->boolean('notification')->default(1);
            $table->tinyInteger('view_count')->default(0);
            $table->boolean('pin')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('solution_id')->references('id')->on('comments');
        });

        if (config('database.default') != 'sqlite') {
            DB::statement('ALTER TABLE articles ADD FULLTEXT search(title, content)');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('articles');
    }
}
