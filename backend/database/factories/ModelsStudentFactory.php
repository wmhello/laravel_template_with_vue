<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Student::class, function (Faker $faker) {
    $arrSex = ['男', '女'];
    return [
        //
        'student_name' => $faker->name,
        'student_sex'  => $arrSex[mt_rand(0,1)],
        'student_phone' => get_phone(),
        'student_status' => mt_rand(0,1),
        'student_remark' => str_random(20)
    ];

});
