<?php

use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        //
        'title' => $faker->sentence,
        'author'=> $faker->name,
        'body'=> $faker->paragraph
    ];
});
