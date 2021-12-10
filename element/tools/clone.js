let fs = require('fs')
const readline = require("readline");
const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

const question = rlPromisify(rl.question.bind(rl));
(async () => {
    // 如果首字符不大写 要重新输入  空内容 重新输入
    let moduleName ='';
     do {
       moduleName = await question("请输入模型名称（全部小写）：");
     } while (! moduleName || ! strIsLower(moduleName));

    let routerName ='';
     do {
       routerName = await question("请输入路由名称（建议小写、复数）：");
     } while (! routerName || ! strIsLower(moduleName));

     let pageType = 2
     do {
      pageType = await question("请输入页面类型(1-简单-不包括分页和查询  2-正常-功能全面):")
      pageType = parseInt(pageType)
    } while (pageType !== 1 && pageType !==2 )

    rl.close();

    // 根据模板生成接口文件
    let apiFile = `src/api/${moduleName}.js`
    let modelFile = `src/model/${moduleName}.js`
    let result = fs.readFileSync('src/api/template.js')
    let code = result.toString();
    let str = code.replace('##name##', routerName)
    fs.writeFileSync(apiFile, str)
    console.log('生成接口文件成功')

    // 根据模型模板文件生成模型文件
    result = fs.readFileSync('src/model/template.js')
    code = result.toString();
    fs.writeFileSync(modelFile, code)
    console.log('生成模型文件成功')
    //根据模型名生成页面视图

    let path = `src/views/${moduleName}`
    if (fs.existsSync(path)) {
       deleteall(path)
     }
    fs.mkdirSync(path)
    if (pageType===2) {
      result = fs.readFileSync('src/views/template/index.vue')
    } else {
      result = fs.readFileSync('src/views/template/simple.vue')
    }

    let component = moduleName.charAt(0).toUpperCase() + moduleName.slice(1) + 'Index'
    result = result.toString();
    code = result.replace('##component##', component)
    code = code.replace('##name##', moduleName)
    fs.writeFileSync(`src/views/${moduleName}/index.vue`, code)
    console.log('生成页面文件成功');
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

function strIsLower(str){
  let bool = true;
  for(let i =0; i<=str.length; i++) {
    if (str.charCodeAt(i) < 97 || str.charCodeAt > 122) {
      bool = false;
      break;
    }
  }
  return bool;
}
