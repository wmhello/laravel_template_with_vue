/*
 * @Author: wmhello 871228582@qq.com
 * @Date: 2022-08-20 00:34:28
 * @LastEditors: wmhello 871228582@qq.com
 * @LastEditTime: 2022-08-20 01:17:02
 * @FilePath: \element\src\utils\socket.js
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */
import setting from "@/settings";
function initWebpack() {//初始化websocket
  if ("WebSocket" in window && setting.isWebsocket) {
    // 打开WebSocket

    let url = setting.websocketUrl || "ws://127.0.0.1:1800"
    this.websock = new WebSocket(url);//这里面的this都指向vue
    this.websock.onopen = websocketopen.bind(this);
    this.websock.onmessage = websocketonmessage;
    this.websock.onclose = websocketclose;
    this.websock.onerror = websocketerror;
  }
}
function websocketopen() {//打开
  let that = this
  setInterval(() => {
    that.websock.send(JSON.stringify({
      type: 'ping'
    }))
  }, 30000)
}


function websocketonmessage(e) { //数据接收
  var res = JSON.parse(e.data);
  if (res && res.type === 'init') {
    // 初始化,保留client_id,准备去做绑定数据  开发阶段显示提示
    if (process.env.NODE_ENV === 'development') {
      console.log("初始化并返回client_id");
    }
    window.localStorage.setItem('uuid', res.client_id)
  } else {
    // 存在特定业务的处理逻辑，则调用
    let uuid = window.localStorage.getItem('uuid')
    if (window.websocketHandle && window.websocketHandle[res.type]) {
      // 后端放回的数据类型，进行识别
      // 如果用户标识为all 或者说没有uuid，则发送给全部
      if (res.select === 'all' || !res.hasOwnProperty('uuid')) {
        window.websocketHandle[res.type](res.content, res)
      } else {
        // 发送到指定的设备
        if (res.select === 'one' && typeof res.uuid === 'string' && res.uuid === uuid) {
          window.websocketHandle[res.type](res.content, res)
        }
        // 发送到组，包含多个设备
        if (res.select === 'includes' && Array.isArray(res.uuid) && res.uuid.includes(uuid)) {
          window.websocketHandle[res.type](res.content, res)
        }
        // 除了本设备以外
        if (res.select === "except" && typeof res.uuid === 'string' && res.uuid !== uuid) {
          window.websocketHandle[res.type](res.content, res)
        }
        // 排除本分组内的设备
        if (res.select === "except" && Array.isArray(res.uuid) && !res.uuid.includes(uuid)) {
          window.websocketHandle[res.type](res.content, res)
        }
      }
    } else {
      if (process.env.NODE_ENV === 'development') {
        console.log("没有注册处理程序");
        console.log(res);
      }
    }
  }

}
function websocketclose() {  //关闭
  console.log("WebSocket关闭");
  // initWebpack(this.token)
}
function websocketerror() {  //失败
  console.log("WebSocket连接失败");
}

function close() {
  this.websock.close()//关闭websocket
  this.websock.onclose = function (e) {
    if (process.env.NODE_ENV === 'development') {
      console.log("断开连接");
    }
  }
}
export default {
  close,
  initWebpack
}
