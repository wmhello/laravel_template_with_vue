## 写在前面
    2018年的春节假期，受朋友的鼓励和内心的指引，对近两年所学到的知识进行了系统的沉淀和总结。
    从多个项目中提取关键点、抛弃了的业务部分，对底层的功能进行了各类优化和抽象，写成本项目。  
## 1、 当前版本介绍

## 1.1 版本说明

>  当前版本laravel_template_with_vue (3)  

## 1.2  改进说明

####  总体构架
>  1. 修改后端目录为api
>  2. 修改管理端目录为element(UI使用element)
>  3. 增加管理端目录antd(UI使用antd)
>  4. 增加小程序端目录uni-app(UI使用uview)
>  5. 增加独立的公众号目录vant(单页面 UI使用vant)

#### 后端：
> 1.更新larave框架为LTS版本laravel6
>
> 2.更新passport插件到最新版本
>
> 3.完善RBAC管理
>
> 4.增加验证码功能、短信发送功能和第三方登陆等功能

#### 管理端：
>  1. 前端element ui 更新到了2.15.6版本，请参照开发
>  2. 完善RBAC的管理端操作
>  3. 增加简单的内容管理（文章、文章类型、轮播图，使用于小程序和公众号等）
>  6. 配置完善websocket功能，实现聊天室、客服等功能
>  7. 增加微信端的各种配置信息等

#### 小程序端：

> 1.  小程序完善的目录结构和开发功能，直接对接后端接口
>
> 2. 小程序内用户的登陆、获取用户名和手机号码

## 2、系统概述
    项目依托laravel6与vue.js，采用了主流的前后端分离方式来构建，作为程序的起点，你可以在此基础上进行自身业务的扩展。
    后端(api目录)负责OAuth认证、用户授权、第三方用户登录验证和提供API，在此基础上集成excel文件的操作和完善的RBAC管理等基础功能，使用者只需专注于业务api的开发即可。后端整合了laravel-echo-server，实现了websocket。并实现消息的实时推送、为聊天室、客服等功能提供了API，是全网最好的laravel-echo-server教程。
    前端(element目录)负责页面的显示和前端用户权限的控制。项目引入了element UI框架，并已经对用户登录认证、路由、权限等基础功能进行了处理。前端用户的权限不但可以控制系统的导航菜单，而且可以控制到页面按钮、表格等内容的显示。使用者只需要专注于业务界面的开发即可。
    小程序(uni-app目录)主要用户小程序开发，集成了uview，实现了用户的登陆授权和获取手机号等功能，在此基础上，使用时只需要关心业务页面的开发即可以。
    本项目使用广泛，已经在本人的多个项目中商用。
#### 注意事项

>  1. 系统中admin用户为超级管理员，为了方便演示，也是为了供大家使用，发布的版本中，已经屏蔽admin用户的信息修改等功能，实际开发中，用户只需要去相应的前端页面中学校除去屏蔽修改的语句就可以。
>
>  2. 为了使用websocket等功能，需要用户同时修改前后和后端的配置，开启websocket
>
>  ​      3. 为了演示聊天室和客服等功能，用户可以进入系统后首先创建多个用户，并且利用不同的浏览器同时登陆，就可以演示相关功能。

## 3、项目演示与截图
> (管理端 element ui)演示网站(http://elem.halian.net)  
>
> 管理员用户名和密码(admin/123456)  

### 项目截图
#### 登陆页面(可以扩充到使用验证码和手机号码登陆)  
![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/系统登陆.jpg)

管理员面板

![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/2-管理员面板.jpg)

#### 修改个人信息

![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/3-修改个人信息.jpg)

#### 全屏幕操作

![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/4-全屏操作.jpg)

#### 管理员管理

![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/5-管理员管理.jpg)

#### 添加管理员
![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/6-添加管理员.jpg)

#### 导入管理员
![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/7-批量导入管理员.jpg)

#### 角色管理

![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/8-角色管理.jpg)

#### 角色功能设置
![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/9-角色功能设置.jpg)

#### 模块与权限管理

![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/10-模块管理.jpg)

#### 添加新模块

![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/11-添加模块.jpg)

#### 超级管理员界面下的功能
![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/12-超级管理员界面.jpg)

#### 不同角色用户下的功能

![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/13-不同角色用户.jpg)

#### 文章管理以及富文本编辑器  

![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/14-文章管理及富文本.jpg)

####  应用功能-聊天室

![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/15-聊天室.jpg)

####  应用功能-客服(普通用户界面 1对1)

![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/16-客服（1-1）.jpg)

####  应用功能-客服(客服界面 1对多)

![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/17-客服（1-多）.jpg)

#### 快速开发应用
![](https://i.loli.net/2021/11/29/StAMiRD1jPJEc9Z.png)


#### 微信公众号测试号
![微信测试号2.png](https://s2.loli.net/2021/12/10/M2BVRZQDvqjahTo.png)


#### 测试号菜单
![测试号菜单](https://s2.loli.net/2021/12/10/kbzDur6sIhC7FTO.jpg)

#### 测试号H5页面
![测试号H5页面](https://s2.loli.net/2021/12/10/sS2duifVE4B59he.png)



## 4、技术文档
### 部署视频
https://www.bilibili.com/video/BV1qi4y197JF?spm_id_from=333.999.0.0

### [后端快速部署](back.md)

### [前端快速部署](front.md)

### [关键知识点讲述](knowledge.md)

### [业务开发](developer.md)



## 5、 开发视频与在线辅导

> 如果需要购买相应的学习视频  可以光临我的小店（https://yzkjit.taobao.com）
>
> 如果需要技术辅导和支持 可以加我微信(xpyzwm)

### 利用vue.js和vue-element-admin开发管理系统
学习视频：
https://v.qq.com/x/page/i3059zqgj4y.html  
https://v.qq.com/x/page/m3059l9bitb.html

#### 目录

![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/admin-mulu-2.png)

### 利用PHP开发微信公众号
学习视频:
https://url.cn/5d4wWGl?sf=uri

![微信公众号开发目录](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/wx-mulu.png)


### 利用Laravel开发RESTful API
学习视频:
https://v.qq.com/x/page/t3059mfpgkg.html

#### 目录:
1  软件构建与表的设计  
2  迁移表的编写  
3  模拟数据的生成  
4  列表API的编写  
5  新增和修改API的编写  
6  删除API的编写和优化  
7  数据的导入和导出  
8  个性化导入  
9  后台API的书写流程以及示列  
10  passport插件的安装  
11  利用passport生成和注销令牌  
12	令牌的刷新  
13	RBAC权限管理-数据表的建立  
14	RBAC权限管理-逻辑的编写  
15	中间件的编写  
16	封装可以复用的控制器模板  
17	模板控制器的编写（增加、修改和删除功能）  
18	模板控制器的编写（数据的导入和导出）  
19	模板控制器使用以及分析  
20	自定义命令行--command的应用  
21	代码解耦的好帮手--事件系统  
22	广播与消息推送-理论与配置  
23	广播与消息推送的实际应用   
24  利用laravel-echo-server实现消息推送和聊天室功能  
25  laravel中短信发送功能的集成  
26  laravel中邮件发送功能的集成  

### 利用uni-app开发微信小程序(核心知识点)

![](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/mp-mulu.png)


### 在线辅导
 如果你在计算机程序设计的学习与开发的过程中，碰到难点，需要技术指导和相关的开发辅导。可以联系本人，本人将提供有偿的技术支持和辅导（50元/时-100元/小时），辅导的内容包括但不局限于以下（前端、后端PHP、nodejs、数据库、javascript和PHP的程序设计模式、公众号、小程序、vue.js、uni-app等）。


## 6、技术支持
> 欢迎大家来光临我的博客，主要专注于laravel与vue.js的应用
[博客](https://docs.chenbont.com)

> 部署和使用中如果有疑问，可以到项目交流群进行讨论：xpyzwm(wechat)或者关注公众号(computer_life)学习相关基础知识

> ![微信二维码](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/weixin1.png)

> ![全栈开发公众号](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/gzh.jpg)


## 6、打赏
如果我的付出能够帮助到你，我也乐于接受你的帮助，小小的赞赏是我持续进步的动力。

![支付宝支付](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/pay1.jpg)

![微信支付](https://cdn.jsdelivr.net/gh/wmhello/imgs/blog/wx.jpg)

## 7、致谢
  站在巨人之上，我们才能走得更远。项目中使用和借鉴了以下开源框架的实现方法 一并致谢
>- [laravel](https://laravel.com/)
>- [后端excel插件](https://github.com/rap2hpoutre/fast-excel)
>- [vue.js](https://cn.vuejs.org/index.html)
>- [element ui](http://element.eleme.io/#/zh-CN)
>- [vue-router](https://router.vuejs.org/)
>- [vuex](https://vuex.vuejs.org/)
>- [前端构架 vueAdmin-template](https://github.com/PanJiaChen/vueAdmin-template)

# License

[MIT](https://github.com/wmhello/laravel_template_with_vue/blob/master/LICENSE)
