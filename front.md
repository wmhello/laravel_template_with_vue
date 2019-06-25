## 1、安装依赖
`npm  install` 
   
## 2、配置后端服务器
文件位于frontend目录下，.env.development保存开发时的环境，.env.development保存发布后的环境

配置其中的VUE_APP_BASE_API地址，分别指向开发时的本地后端服务器和发布后线上后端服务器。

    
## 3、配置上传文件的服务器地址
文件位于frontend\src\config目录下，_import_development.js保存开发时使用的各种配置，_import_production.js保存发布时使用的各种配置

配置其中的site地址，分别指向开发时的上传文件服务器地址和发布后的上传文件服务器地址（只需要网址）。

## 4、本地开发
`npm run dev`

登录用户名和密码使用后端的用户名和密码

## 5、编译打包
`npm run build`

程序打包到了frontend\dist目录