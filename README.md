## 写在前面
    2018年的春节假期，受朋友的鼓励和内心的指引，对近两年所学到的知识进行了系统的沉淀和总结。
    从多个项目中提取关键点、抛弃了的业务部分，对底层的功能进行了各类优化和抽象，写成本项目。  
## 1、 当前版本介绍
### 1.1 版本说明
>  当前版本laravel_template_with_vue (2.1)  
### 1.2  改进说明
#### 后端：
>  1. 调整xlsx文件的处理插件为fast-excel  
>  2. 数据表部分字段的调整，取消permissions中不常用的method和route_match字段,修改users表中的role字段为roles， roles表中的permission为permissions，使数据表更加规范化     
>  3. 代码层面，资源控制器的功能复用，让代码更简洁和实用，父类Controller中编写好了数据的增删改查和数据表的导入和导出功能，用户可以根据TempController的方式来编写相关代码，可以参考OrderController(订单控制),开箱即用，节省业务的编写时间  
> 4. 完善日志管理的API
> 5. 利用showdoc完成文档构建
> 6. 利用laravel-echo-server,集成websocket,当同一个用户多次登陆，前次登陆的页面，将自动退出。利用webasocket的消息推送来实现
> 7. 利用laravel-echo-server,集成websocket，实现聊天室功能和客服功能

#### 前端：
>  1. 前端element ui 更新到了2.7.2版本，请参照开发
>  2. 集成了同时打开多个页面的功能，多标签功能  
>  3. 集成了全屏操作的功能  
>  4. 增加了日志管理功能  
>  5. 增加了前端开发示列，商品订单管理，代码层面利用mixin功能优化书写
>  6. 接收后端推送的消息，强制下线多次登陆的用户，保证同一个用户在什么时间与地点只能登陆一次。
>  7. 增加了应用事例模块，把具有代表性的聊天室功能和客服功能集成到项目中

#### ToDo：
>  1. 前端增加用户多角色动态切换功能
>  2. 增加成员管理功能，实现微信登录、qq登录等第三方用户登录的功能
>  3. 增加成员注册和使用功能

## 2、系统概述
    项目依托laravel5.5与vue.js，采用了主流的前后端分离方式来构建，作为程序的起点，你可以在此基础上进行自身业务的扩展。
    后端(backend目录)负责OAuth认证、用户授权、第三方用户登录验证和提供API，在此基础上集成了跨域和excel文件的操作等基础功能，使用者只需专注于业务api的开发即可。
    后端(backend目录)整合了laravel-echo-server，实现了websocket。用于消息的实时推送、聊天室、客服等功能，是全网最好的laravel-echo-server教程。
    前端(frontend目录)负责页面的显示和前端用户权限的控制。项目已经引入了element UI框架，并已经对用户登录认证、路由、权限等基础功能进行了处理。
    前端用户的权限不但可以控制系统的导航菜单，而且可以控制到页面按钮、表格等内容的显示。使用者只需要专注于业务界面的开发即可。
    本项目使用广泛，已经在本人的多个项目中商用。
>  第三方登录测试时，可以先进入系统创建一个用户，然后用github登录后绑定刚刚创建的新用户，之后就可以使用github来自动登录了（可以参考版本1，版本2因为项目调整的关系，之后才会增加）

## 3、项目演示与截图
> [element-ui](https://github.com/wmhello/laravel_template_with_vue.git)演示网站(http://vue.ouenyione.com)  
> [antd-for-vue](https://github.com/wmhello/antd_and_html5.git) 演示网站(http://wmhello.wicp.vip)  
> 管理员用户名和密码(871228582@qq.com/123456)  
> 普通用户用户名和密码(786270744@qq.com/123456)

### 项目截图
####  文档
![系统文档](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/doc.png)

####  聊天室
![聊天室](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/chat.png)

####  客服(普通用户界面 1对1)
![客服界面1](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/kefu-user.png)

####  客服(客服界面 1对多)
![客服界面2](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/kefu-service.png)

#### 管理员面板
![管理员面板](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-admin-dashboard.png)

#### 普通用户面板[注意观察系统日志和左侧导航菜单]
![普通用户面板](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-user-dashboard.png)

#### 修改个人信息
![修改个人信息](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-modify-info.png)

#### 全屏幕操作
![全屏幕操作](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-full-screen.png)

#### 用户管理
![用户管理](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-user-list.png)

#### 用户添加
![用户添加](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-user-add.png)

#### 用户数据导出
![用户数据导出](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-user-download.png)

#### 角色管理
![角色管理](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-role-manger.png)

#### 角色功能设置
![角色功能设置](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-role-set-feature.png)

#### 功能管理
![功能管理](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-permission-manger.png)

#### 功能组管理
![功能组管理](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-permission-group.png)

#### 添加新功能
![添加新功能](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-permission-feature.png)

#### 系统日志管理
![系统日志管理](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-log-manger.png)

#### 管理日志的管理
![工作日志的管理](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-log-work.png)

#### 管理员界面下的订单管理
![管理员界面下的订单管理](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-orders-manger.png)

#### 普通用户下的订单管理
![普通用户下的订单管理](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/v2-orders-user.png)

## 4、技术文档
### [1、后端快速部署](back.md)
### [2、前端快速部署](front.md)
### [3、关键知识点讲述](knowledge.md)
### [4、业务开发](developer.md)
### [5、系统视频与在线辅导](vedio.md)
### [6、开发视频](study.md)

## 5、技术支持
> 欢迎大家来光临我的博客，主要专注于laravel与vue.js的应用
[博客](https://wmhello.github.io)

> 如果对您有帮助，您可以点右上角 "Star" 支持一下 谢谢！ ^_^

> 或者您可以 "follow" 一下，我会不断完善该项目

> 开发环境 windows 7  Chrome 63  PHP 7.1.7

> 如有问题请直接在 Issues 中提，或者您发现问题并有非常好的解决方案，欢迎 PR 👍

> __部署和使用中如果有疑问，可以到项目交流群进行讨论：106822531(QQ)或者关注公众号(computer_life)学习相关基础知识

> ![QQ群二维码](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/qq_qrcode.jpg)

> ![全栈开发公众号](https://github.com/wmhello/laravel_template_with_vue/blob/master/Screenshots/gzh.jpg)


## 6、打赏
如果我的付出能够帮助到你，我也乐于接受你的帮助，小小的赞赏是我们持续进步的动力。

![支付宝支付](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/pay1.jpg)
![微信支付](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/wx.jpg)

## 7、致谢
  站在巨人之上，我们才能走得更远。项目中使用和借鉴了以下开源框架的实现方法 一并致谢
>- [laravel](https://laravel.com/)
>- [后端excel插件](https://github.com/rap2hpoutre/fast-excel)
>- [后端跨域](https://github.com/barryvdh/laravel-cors)
>- [API接口文档](https://github.com/star7th/showdoc)
>- [vue.js](https://cn.vuejs.org/index.html)
>- [element ui](http://element.eleme.io/#/zh-CN)
>- [vue-router](https://router.vuejs.org/)
>- [vuex](https://vuex.vuejs.org/)
>- [前端构架 vueAdmin-template](https://github.com/PanJiaChen/vueAdmin-template)

# License

[MIT](https://github.com/wmhello/laravel_template_with_vue/blob/master/LICENSE)
