## 1.apache的配置
~~~
<VirtualHost *:80>
    # 本地目录，注意到后端项目的public目录
    DocumentRoot "D:/laravel_template_with_vue/api/public/"
    # 域名
    ServerName api.temp.test
    ServerAlias api.temp.test
  	Header set Access-Control-Allow-Origin *
    Header set Access-Control-Allow-Credentials false
    Header set Access-Control-Allow-Headers *
    Header set Access-Control-Allow-Methods *
    # 本地目录
    <Directory "D:/laravel_template_with_vue/api/public/">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
~~~


## 2、在后端目录下，安装依赖
~~~
composer  install
~~~

## 3. 新建数据库  
>  数据库中的字符集为utfmb8--UTF-8 Unicode , 排序规则为utf8mb4_unicode_ci

## 4、复制配置文件，生成项目密匙

~~~php
cp .env.example .env  
php artisan key:generate
~~~


## 5.项目配置（数据库和域名配置）
>  .env文件中的APP_URL为后端域名，以http或者https开头
>  
>  配置.env文件中的DB_DATABASE、DB_USERNAME和DB_PASSWORD 设置数据库
>  

## 6、生成项目所需的数据表

~~~
php artisan migrate
~~~

## 7、生成用户数据和各种结构数据

> 用户名/密码: admin/123456

~~~
php artisan db:seed
~~~


## 8、使用OAuth认证，生成passport的密钥
~~~php
php artisan passport:keys
php artisan passport:client --password
~~~

>  生成的密匙填写到.env文件中的OAuth认证这一块的PASSPORT_CLIENT_ID和PASSPORT_CLIENT_SECRET的参数


## 9、消息推送（websocket配置，可以稍后）

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
