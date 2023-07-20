## 1. apache的配置
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


## 2. 在后端目录下，安装依赖
~~~
composer  install
~~~

## 3. 新建数据库  
>  数据库中的字符集为utfmb8--UTF-8 Unicode , 排序规则为utf8mb4_unicode_ci

## 4. 复制配置文件，生成项目密匙

~~~php
cp .env.example .env  
php artisan key:generate
~~~


## 5. 项目配置（数据库和域名配置）
>  .env文件中的APP_URL为后端域名，以http或者https开头
>  
>  配置.env文件中的DB_DATABASE、DB_USERNAME和DB_PASSWORD 设置数据库
>  

## 6. 生成项目所需的数据表

~~~php
php artisan migrate
~~~

## 7. 生成用户数据和各种结构数据

> 用户名/密码: admin/123456

~~~php
php artisan db:seed
~~~


## 8. 使用OAuth认证，生成passport的密钥
~~~php
php artisan passport:keys
php artisan passport:client --password
~~~

>  生成的密匙填写到.env文件中的OAuth认证这一块的PASSPORT_CLIENT_ID和PASSPORT_CLIENT_SECRET的参数

## 9. 建立图像软连接
>  linux上要设置相关目录的权限为777 public目录和storage目录
~~~
php artisan storage:link
~~~

## 10.配置自动生成代码
>  修改后端目录（api）下的config目录中的database.php, 修改'super'关联数组中的'password'选项，设置mysql数据库中root用户名的密码，其余不变
> 注意：只能使用root用户
~~~
   'username' => 'root',
  'password' => 'abc@123456',
~~~


