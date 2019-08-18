<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'nome' => $faker->unique()->word(),
        'preco' => $faker->randomFloat(2, 1, 9999),
        'peso' => $faker->randomFloat(2, 1, 9999),
    ];
});
