<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Permission::class, function (Faker $faker) {
   $methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'] ;
   $pid = random_int(0,8);
   $type = random_int(1,2);
    return [
        //
        'name' => $faker->title,
        'pid'  => $pid,
        'type' => $type,
        'method' => $type == 2 ? $faker->randomElement($methods): '',
        'route_name' => $type == 2 ? $faker->title: '',
     ];
});
