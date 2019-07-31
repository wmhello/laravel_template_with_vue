## 1、安装依赖
`composer  install`

## 2、复制配置文件，进行数据库配置
`cp .env.example .env`  
>  根据需求，配置.env文件中的DB_DATABASE、DB_USERNAME和DB_PASSWORD  
`php artisan key:generate`


## 3、生成项目所需的数据表
`php artisan migrate`

## 4、使用OAuth认证，生成passport的密钥
`php artisan passport:key --force`

`php artisan passport:install --force`

## 5、复制第4步生成的密钥到.env文件中，填写为PERSONAL_CLIENT_SECRET和PASSPORT_CLIENT_SECRET的参数
PERSONAL_CLIENT_ID=1

PERSONAL_CLIENT_SECRET=

PASSPORT_CLIENT_ID=2

PASSPORT_CLIENT_SECRET=

## 6、生成用户数据和各种结构数据
`php artisan db:seed`

用户名和密码在database\seeds\UsersTableSeeder.php文件中明文标记

## 7、配置域名(back.test 或者其他名称)指向后端目录下的public目录(backend/public/)，并在本地hosts文件中添加记录
`127.0.0.1 back.test`

**此步骤是OAuth认证所必须，请务必设置,否则无法登录**

## 8、消息推送

需要根据要求配置laravel-echo-server，全局安装  

`npm install -g laravel-echo-server`  

安装之后，在后端目录(backend)执行初始化  

`laravel-echo-server init`  

相关的配置请参考相关文档，或者加我微信（xpyzwm）交流  

配置完成之后，需要后端目录下启动laravel-echo-server 才能实现聊天、推送等功能

`laravel-echo-server start`

## 9、查看API文档地址

API文档使用了[showdoc](https://github.com/star7th/showdoc)
如果系统部署于Windows服务器,为了保证showdoc的运行，请先检查下列条件：

在php.ini里面把”extension=php_sqlite.dll”和”extension=php_pdo_sqlite.dll”启用以便开启对SQlite的支持；也启用php_mbstring.dll；Linux服务器则不需要此操作。


 假设后端的域名为back.test 则文档地址为http://back.test/showdoc/web/#/1  
 线上文档的话，可以通过进入演示系统的登录界面就可以进入
