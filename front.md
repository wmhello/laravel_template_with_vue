## 1、安装依赖
`npm  install` 

## 2、配置后端服务器
前端位于element目录下，.env.development保存开发时的环境，.env.development保存发布后的环境

配置其中的VUE_APP_BASE_API地址，分别指向开发时的本地后端服务器和发布后线上后端服务器。

## 3、本地开发
`npm run dev`

登录用户名和密码使用后端的用户名和密码

## 4、编译打包
`npm run build`

程序打包到了element\dist目录