/*
 * @Author: wmhello 871228582@qq.com
 * @Date: 2021-10-12 22:49:35
 * @LastEditors: wmhello 871228582@qq.com
 * @LastEditTime: 2022-08-16 15:28:01
 * @FilePath: \element\src\settings.js
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */
module.exports = {
  title: '管理系统',

  /**
   * @type {boolean} true | false
   * @description Whether fix the header
   */
  fixedHeader: false,

  /**
   * @type {boolean} true | false
   * @description Whether show the logo in sidebar
   */
  sidebarLogo: false,
  /**
   * websocket相关的配置
   */
  isWebsocket: true,   // 是否开启websocket支持
  isSingle: true,      // 是否单用户登录
  websocketUrl: "wss://api.wmhello.xyz/wss" // websocket的地址

}
