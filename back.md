## 1、安装依赖
`composer  install` 
   
## 2、复制配置文件，进行数据库配置
`cp .env.example .env`
    
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

## 查看API文档地址
 假设后端的域名为back.test 则文档地址为http://back.test/apidoc/
   
## 编译API文档
  由于文档使用了apidoc 请使用前预先安装apidoc
  
  编译命令为apidoc -i ./ -o public/apidoc/
