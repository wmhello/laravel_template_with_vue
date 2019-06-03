<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Order::class, function (Faker $faker) {
    return [
        'order_number' => $faker->unique()->numerify('order_####'),
        'merchant_number' => $faker->numerify('merchant_####'),
        'merchant_name' => $faker->company(),
        'order_status' => 1,
        'order_time' => $faker->dateTime($max = 'now'),
        'order_remark' => ''
    ];
});
