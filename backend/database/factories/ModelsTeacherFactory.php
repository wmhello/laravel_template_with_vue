<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Teacher::class, function (Faker $faker) {
    $arrSex = ['男', '女'];
    $arrPol = ['中共党员','中共预备党员', '共青团员', '民革党员', '民盟盟员', '民建会员', '民进会员', '农工党党员', '致公党党员', '九三学社社员', '台盟盟员', '无党派人士', '群众'];
    return [
    'name' => $faker->name,
    'sex' => $arrSex[mt_rand(0,count($arrSex)-1)],
    'nation_id' => mt_rand(1,56),
    'birthday' => $faker->date(),
     'work_time' => $faker->date(),
    'political'  => $arrPol[mt_rand(0, count($arrPol)-1)],
    'join_date' => $faker->date(),
    'card_id' => get_card_id(),
    'phone' => get_phone(),
    'phone2' => get_phone() ,
    'type' => 1,
    'open_id'=> str_random(28),
    'state' => 1,
    'teaching_id' => mt_rand(1,17)
    ];
});
