<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Prepare seeding
         */
        $faker = Faker::create();
        if (config('database.default') != 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

//        Not required for Laravel 5.2
//        @see https://laravel.com/docs/5.2/upgrade#upgrade-5.2.0
//        Model::unguard();

        /*
         * Seeding users table
         */
        App\User::truncate();
        factory(App\User::class)->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password')
        ]);
        factory(App\User::class, 9)->create();
        $this->command->info('users table seeded');

        /**
         * Seeding roles table
         */
        Bican\Roles\Models\Role::truncate();
        DB::table('role_user')->truncate();
        $adminRole = Bican\Roles\Models\Role::create([
            'name' => 'Admin',
            'slug' => 'admin'
        ]);
        $memberRole = Bican\Roles\Models\Role::create([
            'name' => 'Member',
            'slug' => 'member'
        ]);

        App\User::where('email', '!=', 'john@example.com')->get()->map(function($user) use($memberRole) {
            $user->attachRole($memberRole);
        });

        App\User::whereEmail('john@example.com')->get()->map(function($user) use($adminRole){
            $user->attachRole($adminRole);
        });
        $this->command->info('roles table seeded');

        /*
         * Seeding articles table
         */
        App\Article::truncate();
        $users = App\User::all();

        $users->each(function($user) use($faker) {
            $user->articles()->save(
                factory(App\Article::class)->make()
            );
            $user->articles()->save(
                factory(App\Article::class)->make()
            );
        });
        $this->command->info('articles table seeded');

        /**
         * Seeding comments table
         */
        App\Comment::truncate();
        $articles = App\Article::all();

        $articles->each(function($article) use($faker, $users) {
            $article->comments()->save(
                factory(App\Comment::class)->make([
                    'author_id' => $faker->randomElement($users->lists('id')->toArray())
                ])
            );
        });
        $this->command->info('comments table seeded');

        /*
         * Seeding tags table
         */
        App\Tag::truncate();
        DB::table('article_tag')->truncate();
        $rawTags = config('project.tags');

        foreach($rawTags as $tag) {
            App\Tag::create([
                'name' => $tag,
                'slug' => str_slug($tag)
            ]);
        }

        $tags = App\Tag::all();

        foreach($articles as $article) {
            $article->tags()->attach(
                $faker->randomElements(
                    $tags->lists('id')->toArray(),
                    $faker->randomElement([1,2,3])
                )
            );
        }

        $this->command->info('tags table seeded');

        /*
         * Seeding attachments table
         */
        App\Attachment::truncate();
        if (! File::isDirectory(attachment_path())) {
            File::deleteDirectory(attachment_path(), true);
        }

        $articles->each(function($article) use($faker) {
            $article->attachments()->save(
                factory(App\Attachment::class)->make()
            );
        });

        $files = App\Attachment::lists('name');

        if (! File::isDirectory(attachment_path())) {
            File::makeDirectory(attachment_path(), 777, true);
        }

        foreach($files as $file) {
            File::put(attachment_path($file), '');
        }

        $this->command->info('attachments table seeded');

        /**
         * Close seeding
         */
//        Not required for Laravel 5.2
//        @see https://laravel.com/docs/5.2/upgrade#upgrade-5.2.0
//        Model::reguard();
        if (config('database.default') != 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
