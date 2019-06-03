### 版本说明
>  当前版本laravel_template_with_vue (2.0)  

### 前端介绍   
>  本系统基于前端项目[vue-admin-template](http://panjiachen.github.io/vue-admin-template)改进而来，整个多个功能模块，配合后端一起使用，形成一个管理系统的基础模板，让我们可以直接在这个系统上开发项目。
#### 集成功能：  
>  1. 多标签导航  
>  2. 全屏功能

#### 改进功能  
>  1. 动态路由：经过管理员的配置，不同角色的用户可以显示不同的页面（前端路由），通用性更好  
>  2. 按钮级别的权限控制，可根据用户的权限来决定按钮或者指定元素的显示或隐藏  
>  3. 改进了用户自身信息的修改  
>  4. 源代码角度各种功能和组件的复用，可以写更少的代码，做更多的开发

### 前端安装
>  1. 安装依赖
~~~
npm install
~~~
>  2. 配置域名接口  
开发环境下的后端域名配置 ：  
.env.development文件下
的VUE_APP_BASE_API参数  
开发环境下的文件上传域名配置：  
src目录下的config下的_import_development.js下的site参数  
生产环境下的后端域名配置 ：  
.env.production文件下
的VUE_APP_BASE_API参数  
生产环境下的文件上传域名配置：  
src目录下的config下的_import_production.js下的site参数    
>  3. 前端开发  
~~~
npm run dev
~~~
>  4. 前端编译
~~~
npm run build
~~~
> 代码编译后直接放置于dist目录下，dist目录被配置了可以直接提交到git代码托管网站，如不需要请自行添加到.gitignore文件。
