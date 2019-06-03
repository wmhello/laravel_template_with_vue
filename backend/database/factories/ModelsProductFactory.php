<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Product::class, function (Faker $faker) {
    $amount =  random_int(1,1000);
    return [
        //
        'product_number' => $faker->numerify('product_####'),
        'product_img' => $faker->imageUrl(300, 200),
        'product_name' => $faker->numerify('product_????'),
        'order_id' => random_int(1,46),
        'product_amount' => $amount,
        'remain_amount' => $amount,
        'product_remark' => ''
    ];
});
