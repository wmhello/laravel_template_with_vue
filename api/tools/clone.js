let fs = require('fs')
const readline = require("readline");
let temp = require('./template').temp;
const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

// 执行命令
let command = require('./cmd');
const question = rlPromisify(rl.question.bind(rl));
(async () => {
    let path = await question("请输入模型所在的目录（app下 默认为Models）：");
    if (path.length===0) {
        path  = 'Models'
    }
    // 如果首字符不大写 要重新输入  空内容 重新输入
    let moduleName ='';
     do {
       moduleName = await question("请输入模型名称（首字母大写）：");
     } while (! moduleName || (moduleName.charAt(0)>'Z' || moduleName.charAt(0) <'A'));

    let routerName ='';
     do {
       routerName = await question("请输入路由名称（建议小写、复数）：");
     } while (! moduleName);
    rl.close();
    // 生成模型和迁移表
    let cmd = `php artisan make:model ${path}\\${moduleName} -m`
    command(cmd).then(()=> {
      let modelFile = `app/${path}/${moduleName}.php`
      let templateFile = `app/${path}/Template.php`
      let result = fs.readFileSync(templateFile).toString()
      let code = result.replace('##name##', moduleName)
      if (fs.existsSync(modelFile)) {
        fs.unlinkSync(modelFile);
      }
      fs.writeFileSync(modelFile, code)

     // 根据模型来创建API资源
      modelFile = `app/http/Resources/${moduleName}.php`
      templateFile = `app/http/Resources/Template.php`
      result = fs.readFileSync(templateFile).toString()
      code = result.replace('##name##', moduleName)
      fs.writeFileSync(modelFile, code)

      // 根据模型来创建API资源集合
      modelFile = `app/http/Resources/${moduleName}Collection.php`
      templateFile = `app/http/Resources/TemplateCollection.php`
      result = fs.readFileSync(templateFile).toString()
      code = result.replace('##name##', moduleName)
      fs.writeFileSync(modelFile, code)
       console.log('API资源创建完成')
      //根据模板来生成资源控制器

      modelFile = `app/http/Controllers/Admin/${moduleName}Controller.php`
      templateFile = `app/http/Controllers/Admin/Template.php`
      result = fs.readFileSync(templateFile).toString()
      code = result.replace(/##name##/g, moduleName)
      fs.writeFileSync(modelFile, code)
      console.log('API资源控制器创建完成')

        code = temp.route
        code = code.replace('##routeName##', routerName)
        code = code.replace('##name##', moduleName)
        fs.appendFile("routes/api.php",code , (error)  => {
          if (error) return console.log("追加文件失败" + error.message);
          console.log("路由添加成功");
        });
    })
})();




// 删除文件夹以及下面的内容
function deleteall(path) {
  var files = [];
  if(fs.existsSync(path)) {
    files = fs.readdirSync(path);
    files.forEach(function(file, index) {
      var curPath = path + "/" + file;
      if(fs.statSync(curPath).isDirectory()) { // recurse
        deleteall(curPath);
      } else { // delete file
        fs.unlinkSync(curPath);
      }
    });
    fs.rmdirSync(path);
  }
};



function rlPromisify(fn) {
    return async (...args) => {
        return new Promise(resolve => fn(...args, resolve));
    };
}
