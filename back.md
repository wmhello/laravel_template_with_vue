## 1、安装依赖
`composer  install` 
   
## 2、复制配置文件，进行数据库配置
`cp .env.example .env`
    
## 3、生成项目所需的数据表
`php artisan migrate`

## 4、生成passport的密钥
`php artisan passport:key --force`
   
`php artisan passport:install --force`

## 5、复制第4步生成的密钥到.env文件中，填写为PERSONAL_Client_Secret和PASSPORT_Client_Secret的参数
PERSONAL_Client_ID=1   

PERSONAL_Client_Secret=

PASSPORT_Client_ID=2 

PASSPORT_Client_Secret=

## 6、生成用户数据和各种结构数据
`php artisan db:seed` 
用户名和密码在database\seeds\UsersTableSeeder.php文件中明文标记

## 查看API文档地址
 假设后端的域名为http://localhost  则文档地址为http://localhost/apidoc/
   
## 编译API文档
  由于文档使用了apidoc 请使用前预先安装apidoc
  
  编译命令为apidoc -i ./ -o public/apidoc/