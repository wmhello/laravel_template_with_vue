## 1、写在前面
    2018年的春节假期，受朋友的鼓励和内心的指引，对近两年所学到的知识进行了系统的沉淀和总结。
    从多个项目中提取关键点、抛弃了的业务部分，对底层的功能进行了各类优化和抽象，写成本项目。
    
## 2、系统概述
    项目依托laravel5.5与vue.js，采用了主流的前后端分离方式来构建，作为程序的起点，你可以在此基础上进行自身业务的扩展。
    后端(backend目录)负责OAuth认证、用户授权和提供API，在此基础上集成了跨域和excel文件的操作等基础功能，使用者只需专注于业务api的开发即可。
    前端(frontend目录)负责页面的显示和前端用户权限的控制。项目已经引入了element UI框架，并已经对用户登录认证、路由、权限等基础功能进行了处理。
    前端用户的权限不但可以控制系统的导航菜单，而且可以控制到页面按钮、表格等内容的显示。使用者只需要专注于业务界面的开发即可。
    本项目使用广泛，已经在本人的多个项目中商用。
    
## 3、项目演示与截图
> 演示网站(http://front.ynxpyz.cn)  
> 管理员用户名和密码(871228582@qq.com/123456)  
> 普通用户用户名和密码(786270744@qq.com/123456)

### 项目截图

#### 后端API文档
![后端API文档](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/apidoc.png)

#### 管理员面板
![管理员面板](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/dashboard.png)

#### 普通用户面板[注意观察按钮和左侧导航菜单]
![普通用户面板](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/dishboard-user.png)

#### 用户管理
![用户管理](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/user-list.png)

#### 用户添加
![用户添加](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/user-add.png)

#### 用户数据导出
![用户数据导出](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/user-download.png)

#### 角色管理
![角色管理](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/role-manger.png)

#### 角色功能设置
![角色功能设置](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/role-set-feature.png)

#### 功能管理
![功能管理](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/permission-manger.png)

#### 功能组管理
![功能组管理](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/permission-group.png)

#### 添加新功能
![添加新功能](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/permission-feature.png)

#### 管理员界面下的学期管理
![管理员界面下的学期管理](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/session-admin.png)

#### 普通用户界面下的学期管理
![普通用户界面下的学期管理](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/session-user.png)

## 4、技术文档
### [1、后端快速部署](back.md)
### [2、前端快速部署](front.md)
### [3、关键知识点讲述](knowledge.md)
### [4、业务开发](developer.md)
### [5、系统视频](vedio.md)

## 5、技术支持
> 如果对您有帮助，您可以点右上角 "Star" 支持一下 谢谢！ ^_^

> 或者您可以 "follow" 一下，我会不断完善该项目

> 开发环境 windows 7  Chrome 63  PHP 7.1.7

> 如有问题请直接在 Issues 中提，或者您发现问题并有非常好的解决方案，欢迎 PR 👍

> __部署和使用中如果有疑问，可以到项目交流群进行讨论：106822531(QQ)__

> ![QQ群二维码](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/qq_qrcode.jpg)

## 6、打赏
如果我的付出能够帮助到你，我也乐于接受你的帮助，小小的赞赏是我们持续进步的动力。

![支付宝支付](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/pay1.jpg)
![微信支付](https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/wx.jpg)

## 7、致谢
  站在巨人之上，我们才能走得更远。项目中使用和借鉴了以下开源框架的实现方法 一并致谢
>- [laravel](https://laravel.com/) 
>- [后端excel插件](https://github.com/Maatwebsite/Laravel-Excel)
>- [后端跨域](https://github.com/barryvdh/laravel-cors)
>- [API接口文档书写](http://apidocjs.com/)
>- [vue.js](https://cn.vuejs.org/index.html)
>- [element ui](http://element.eleme.io/#/zh-CN) 
>- [vue-router](https://router.vuejs.org/)
>- [vuex](https://vuex.vuejs.org/)
>- [前端构架 vueAdmin-template](https://github.com/PanJiaChen/vueAdmin-template)
>- [前端权限控制 Vue-Access-control](https://github.com/tower1229/Vue-Access-Control)

# License

[MIT](https://github.com/wmhello/laravel_template_with_vue/blob/master/LICENSE)