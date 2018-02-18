<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>绿钻“高富帅”行动</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0" />
    <link rel="stylesheet" href="http://imgcache.gtimg.cn/mediastyle/mobile/event/20150114_spring_promotion/index.css">
    <link href="https://cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
</head>
<body class="bodybox">
<script>
    window.debug = true;
</script>
<div class="wrap" id="wrap">

    <div class="item item-1">
        <div class="box">
            <h1 class="hide">你是否也试过漫漫长夜一个人过…</h1>
            <div class="mod_container">
                <p class="sprite text_1"></p>
                <p class="sprite text_2"></p>
                <div class="sprite scene"></div>
                <div class="text_3 sprite_global"></div>
                <div class="text_4 sprite_global"></div>
                <div class="text_5 sprite_global"></div>
            </div>
        </div>
    </div>

    <div class="item item-2">
        <div class="box">
            <h1 class="hide">救星驾到 秒变高富帅秘诀 </h1>
            <div class="mod_container">
                <p class="sprite text_1"></p>
                <p class="sprite text_2"></p>
                <div class="sprite scene"></div>
            </div>
        </div>
    </div>

    <div class="item item-3">
        <div class="box">
            <h1 class="hide">尊贵标识 终于，成为有身份的人！ </h1>
            <div class="mod_container">
                <p class="sprite text_1"></p>
                <p class="sprite text_2"></p>
                <div class="sprite scene"></div>
            </div>
        </div>
    </div>

    <div class="item item-4">
        <div class="box">
            <h1 class="hide">最有品味 追求高品质，听歌只听无损音质</h1>
            <div class="mod_container">
                <p class="sprite text_1"></p>
                <p class="sprite text_2"></p>
                <div class="sprite scene"><i class="sprite_global"></i><i class="sprite_global"></i><i class="sprite_global"></i></div>
                <div class="desk sprite_global"></div>
            </div>
        </div>
    </div>

    <div class="item item-5">
        <div class="box">
            <h1 class="hide">把妹神器 以歌传情，送她空间背景音乐 </h1>
            <div class="mod_container">
                <p class="sprite text_1"></p>
                <p class="sprite text_2"></p>
                <div class="sprite scene">
                    <i class="icon_zone sprite_global"></i>
                    <i class="sprite_global"></i>
                    <i class="sprite_global"></i>
                    <i class="sprite_global"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="item item-6">
        <div class="box">
            <h1 class="hide">把妹成功！ 谢谢绿钻，让我登上人生巅峰 </h1>
            <div class="mod_container">
                <p class="sprite text_1"></p>
                <p class="sprite text_2"></p>
                <div class="sprite scene"></div>
                <p class="text_3">绿钻邀您加入高富帅行列<br/>高高兴兴，回家过年！</p>
                <i class="boy_girl"></i>
            </div>
        </div>
    </div>

    <div class="item item-7">
        <h1 class="hide">加入绿钻 成为QQ音乐世界VIP，独享高大上特权</h1>
        <div class="box">
            <div class="mod_container">
                <p class="sprite text_1"></p>
                <p class="sprite text_2"></p>
                <div class="sprite scene">
                    <img src="http://imgcache.gtimg.cn/mediastyle/mobile/event/20150114_spring_promotion/img/man_1.png"/>
                    <img src="http://imgcache.gtimg.cn/mediastyle/mobile/event/20150114_spring_promotion/img/man_2.png"/>
                    <img src="http://imgcache.gtimg.cn/mediastyle/mobile/event/20150114_spring_promotion/img/man_3.png"/>
                    <img src="http://imgcache.gtimg.cn/mediastyle/mobile/event/20150114_spring_promotion/img/man_4.png"/>
                    <i class="tips_1"></i>
                    <i class="tips_2"></i>
                </div>
                <a class="btn_open_vip" href="javascript:;" id="J_openvip">立即开通</a>
            </div>
        </div>
    </div>

    <div class="item item-8">
        <div class="box">
            <i class="music_logo sprite_global"></i>
            <p class="anim text_1">你是第<strong id="J_joinNum"></strong>位参与者</p>
            <p class="anim text_2">分享即有机会得iPhone6</p>
            <div class="anim scene"><i></i></div>
            <a class="anim btn_get_gift" href="javascript:;" id="J_getGift">我也要赢iPhone6</a>
            <p class="anim text_3">每天送出1台，助力高富帅，回家好过年！</p>

            <p>{{$user->getId()}}</p>
        </div>
    </div>

    <div class="item item-8">
        <div class="box">
            <i class="music_logo sprite_global"></i>
            <p class="anim text_1">你是第<strong id="J_joinNum"></strong>位参与者</p>
            <p class="anim text_2">分享即有机会得iPhone6</p>
            <div class="anim scene"><i></i></div>
            <a class="anim btn_get_gift" href="javascript:;" id="J_getGift">我也要赢iPhone6</a>
            <p class="anim text_3">每天送出1台，助力高富帅，回家好过年！</p>

            <p>{{$user->getId()}}</p>
        </div>
    </div>

</div>

<!-- 加载提示 _S -->
<div class="global">
    <div class="slider"><span class="sprite_global"></span></div>
</div>
<div class="mod_loading" id="loading" style="display:none">
    <div class="content">
        <div class="progress"><span id="progress_bg" style="width:0;"></span></div>
        <p>Loading...</p>
    </div>
</div>
<!-- 加载提示 _E -->

<audio autoplay="" loop="loop" style="height:0;width:0;display:none" src="audio/2.mp3" id="music"></audio>
<!-- 播放状态 _S -->
<!-- btn_pause为暂停状态 -->
<div class="sprite_global btn_play" id="J_btnMusic" ><i class="sprite_global"></i></div>
<!-- 播放状态 _E -->
<!-- 滑块 _E -->
<div class="m_share" style="display:none;" id="shareLayer">
    <!--这里的箭头指向要做判断，不同的平台指向不同-->
    <!--箭头向上-->
    <img id="shareimgup" src="http://imgcache.qq.com/mediastyle/mobile/event/20140318_ceremony_live/img/share_top.png?max_age=2592000" class="top" alt="点击上方分享按钮分享"/>
    <!--箭头向下-->
    <img id="shareimgdown" src="http://imgcache.qq.com/mediastyle/mobile/event/20140318_ceremony_live/img/share_bottom.png?max_age=2592000" class="bottom" alt="点击下方分享按钮分享"  style="display:none;"/>
</div>


<script type="text/javascript" src="js/iSlider.js"></script>

<script>

    //demo
    //用法
    var myslider=new iSlider({
        wrap:'#wrap',
        item:'.item',
        onslide:function (index) {
            if (index == 7) {
                document.getElementById('J_joinNum').innerHTML=parseInt(Math.random()*10000);
            }
        }
    });
    var isPlayMusic = true;
    var btnMusic = document.getElementById('J_btnMusic');
    var music = document.getElementById('music');
        btnMusic.onclick = function() {
      if (isPlayMusic) {
          isPlayMusic = false;
          music.pause();
          console.log('关闭音乐')
      } else {
          isPlayMusic = true;
          music.play();
          console.log('开启音乐')
      }
    }
    console.info(myslider)



</script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    wx.config(<?php echo $js->config(array('onMenuShareQQ', 'onMenuShareWeibo', 'hideOptionMenu', 'showMenuItems'), true) ?>);
    wx.ready(function() {
//        wx.hideOptionMenu();
        wx.showMenuItems({
            menuList: ['menuItem:share:timeline', 'menuItem:share:qq']
        });
    })
</script>
</body>
</html>