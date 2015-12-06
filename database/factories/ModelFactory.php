<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->safeEmail,
        'password'       => bcrypt('password'),
        'remember_token' => str_random(60),
    ];
});

$factory->define(App\Article::class, function (Faker\Generator $faker) {
    return [
        'title'   => $faker->sentence(),
        'content' => $faker->paragraph(),
    ];
});

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    return [
//        'title'   => $faker->sentence,
        'content' => $faker->paragraph,
    ];
});

$factory->define(App\Tag::class, function (Faker\Generator $faker) {
    $name = ucfirst($faker->optional(0.9, 'Laravel')->word);

    return [
        'name' => $name,
        'slug' => str_slug($name),
    ];
});

$factory->define(App\Attachment::class, function (Faker\Generator $faker) {
    return [
        'name' => sprintf("%s.%s", str_random(), $faker->randomElement(['png', 'jpg'])),
    ];
});

