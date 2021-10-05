## 1、安装依赖
~~~
composer  install
~~~

## 2、复制配置文件，进行数据库配置

> 根据需求，配置.env文件中的DB_DATABASE、DB_USERNAME和DB_PASSWORD  

~~~php
cp .env.example .env  
php artisan key:generate
~~~

## 3、生成项目所需的数据表

~~~
`php artisan migrate`
~~~

## 4、使用OAuth认证，生成passport的密钥
~~~php
php artisan passport:key --force`
php artisan passport:install --force`
~~~

## 5、复制第4步生成的密钥到.env文件中，填写为PERSONAL_CLIENT_SECRET和PASSPORT_CLIENT_SECRET的参数

~~~
PERSONAL_CLIENT_ID=1
PERSONAL_CLIENT_SECRET=
PASSPORT_CLIENT_ID=2
PASSPORT_CLIENT_SECRET=
~~~

## 6、生成用户数据和各种结构数据

> 用户名/密码: admin/123456

~~~
php artisan db:seed
~~~

## 7、配置.env文件中的APP_URL为后端域名，以http或者https开头
## 8、消息推送

需要根据要求配置laravel-echo-server，全局安装  

~~~
npm install -g laravel-echo-server
~~~

安装之后，在后端目录(backend)执行初始化  

~~~
aravel-echo-server init
~~~

相关的配置请参考相关文档，或者加我微信（xpyzwm）交流  

配置完成之后，需要后端目录下启动laravel-echo-server 才能实现聊天、推送等功能

~~~
laravel-echo-server start
~~~
