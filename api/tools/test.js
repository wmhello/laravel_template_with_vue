
// var readlineSync = require('readline-sync');
//
// // Wait for user's response.
// var userName = readlineSync.question('请输入模型路径? ');
// console.log('Hi ' + userName + '!');


const readline = require("readline");

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

function rlPromisify(fn) {
    return async (...args) => {
        return new Promise(resolve => fn(...args, resolve));
    };
}
const question = rlPromisify(rl.question.bind(rl));
(async () => {
    const answer = await question("你好，你是谁：");
    console.log(`你是：${answer}`);
    rl.close();
})();
