"use strict";
// 执行本地命令
var exec = require("child_process").exec;

module.exports = function myTest(cmd) {

    return new Promise(function(resolve, reject) {

        // var cmd = "ipconfig";
        exec(cmd,{
            maxBuffer: 1024 * 2000
        }, function(err, stdout, stderr) {
            if (err) {
                console.log(err);
                reject(err);
            } else if (stderr.lenght > 0) {
                reject(new Error(stderr.toString()));
            } else {
                console.log(stdout);
                resolve();
            }
        });
    });
};
