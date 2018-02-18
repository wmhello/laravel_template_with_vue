<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>我的名片</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0" />
    <link rel="stylesheet" href="css/weui/dist/style/weui.min.css">
</head>
<body >
<div class="page input">

    <div class="weui-cells__title">基本信息</div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell weui-cell_vcode">
            <div class="weui-cell__hd"><label class="weui-label">我的名片</label></div>
            <div class="weui-cell__ft" style="float:right">
                <img class="weui-vcode-img" src="http://wmhello.tunnel.qydev.com/image/1.png">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label" for="name">姓名</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" id="name"  placeholder="姓名">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label" for="telphone">手机号</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" id="telphone" type="number" pattern="[0-9]*" placeholder="手机">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label" for="company">公司名称</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" id="company"  placeholder="公司名称">
            </div>
        </div>

    </div>
    <div class="weui-cells__title">个人简介</div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <textarea class="weui-textarea" placeholder="请输入文本" rows="3"></textarea>
                <div class="weui-textarea-counter"><span>0</span>/200</div>
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="javascript:" id="showTooltips">保存名片</a>
    </div>
</div>


<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    wx.config(<?php echo $js->config(array('onMenuShareQQ', 'onMenuShareWeibo', 'hideOptionMenu', 'showMenuItems'), true) ?>);
    wx.ready(function() {
        wx.hideOptionMenu();
        wx.showMenuItems({
            menuList: ['menuItem:share:timeline', 'menuItem:share:qq']
        });
    })
</script>
</body>
</html>