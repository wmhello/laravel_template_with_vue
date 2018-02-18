<?php

namespace App\Http\Controllers;


use EasyWeChat\OfficialAccount\Application;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    public  $menu;

    /**
     * MenuController constructor.
     * @param $menu
     */
    public function __construct(Application $app)
    {
        $this->menu = $app->menu;
    }

    public function menu()
    {
        $buttons = [
            [
                "type" => "click",
                "name" => "点我试试",
                "key"  => "YOU_CLICK_ME"
            ],
            [
                "name"       => "二级菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "百度",
                        "url"  => "http://www.baidu.com/"
                    ],
                    [
                        "type" => "view",
                        "name" => "授权页面",
                        "url"  => "http://wmhello.tunnel.qydev.com/view"
                    ],
                    [
                        "type" => "click",
                        "name" => "赞一下我们",
                        "key" => "YOU_VOTE_ME"
                    ],
                ],
            ],
            [
                "type" => "view",
                "name" => "修改名片",
                "url"  => "http://wmhello.tunnel.qydev.com/view1"
            ],
        ];
        $this->menu->create($buttons);
    }

    public function all()
    {
        $result = $this->menu->list();
        return $result;
    }
}
