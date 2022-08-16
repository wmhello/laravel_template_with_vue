/*
 * @Author: wmhello 871228582@qq.com
 * @Date: 2022-08-07 13:53:08
 * @LastEditors: wmhello 871228582@qq.com
 * @LastEditTime: 2022-08-16 15:36:57
 * @FilePath: \upload_frontend\src\utils\websocket.js
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */
import setting from "@/settings";
if ("WebSocket" in window && setting.isWebsocket) {
  // alert("WebSocket is supported by your Browser!");
  // 打开WebSocket

  let url = setting.websocketUrl || "ws://127.0.0.1:1800"
  window.ws = new WebSocket(url);

  window.ws.onerror = (event) => {
    // 初始化连接出错
    delete window.websocketHandle
    console.log("websocket启动出错，将关闭相关的功能");
  }

  window.ws.onopen = function (e) {
    // 50秒钟发心跳设置
    window.ws.timer1 = setInterval(() => {
      window.ws.send(JSON.stringify({
        type: 'ping'
      }))
    }, 30000)
  };

  window.ws.onmessage = function (e) {

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
  };
  window.ws.onclose = function () {
    // websocket is closed
    if (process.env.NODE_ENV === 'development') {
      console.log("断开连接");
    }
    window.clearInterval(window.ws.timer1)
  };
} else {
  // The browser doesn't support WebSocket
  alert("当前浏览器不支持WebSocket");
}
