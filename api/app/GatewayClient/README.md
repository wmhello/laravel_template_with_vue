# GatewayClient

GatewayWorker1.0请使用[1.0版本的GatewayClient](https://github.com/walkor/GatewayClient/releases/tag/v1.0)

GatewayWorker2.0.1-2.0.4请使用[2.0.4版本的GatewayClient](https://github.com/walkor/GatewayClient/releases/tag/2.0.4)

GatewayWorker2.0.5-2.0.6版本请使用[2.0.6版本的GatewayClient](https://github.com/walkor/GatewayClient/releases/tag/2.0.6)

GatewayWorker2.0.7版本请使用 [2.0.7版本的GatewayClient](https://github.com/walkor/GatewayClient/releases/tag/v2.0.7)

GatewayWorker3.0.0-3.0.7版本请使用 [3.0.0版本的GatewayClient](https://github.com/walkor/GatewayClient/releases/tag/v3.0.0)<br>

GatewayWorker3.0.8及以上版本请使用 [3.0.13版本的GatewayClient](https://github.com/walkor/GatewayClient/releases/tag/v3.0.13)<br>

注意：GatewayClient3.0.0以后支持composer并加了命名空间```GatewayClient``` <br>

[如何查看GatewayWorker版本请点击这里](http://doc2.workerman.net/get-gateway-version.html)

## 安装
**方法一**
```
composer require workerman/gatewayclient
```
使用时引入`vendor/autoload.php` 类似如下：
```php
use GatewayClient\Gateway;
require_once '真实路径/vendor/autoload.php';
```

**方法二**
下载源文件到任意目录，手动引入 `GatewayClient/Gateway.php`, 类似如下：
```php
use GatewayClient\Gateway;
require_once '真实路径/GatewayClient/Gateway.php';
```

## 使用
```php
// GatewayClient 3.0.0版本以后加了命名空间
use GatewayClient\Gateway;

// composer安装
require_once '真实路径/vendor/autoload.php';

// 源文件引用
//require_once '真实路径/GatewayClient/Gateway.php';

/**
 * === 指定registerAddress表明与哪个GatewayWorker(集群)通讯。===
 * GatewayWorker里用Register服务来区分集群，即一个GatewayWorker(集群)只有一个Register服务，
 * GatewayClient要与之通讯必须知道这个Register服务地址才能通讯，这个地址格式为 ip:端口 ，
 * 其中ip为Register服务运行的ip(如果GatewayWorker是单机部署则ip就是运行GatewayWorker的服务器ip)，
 * 端口是对应ip的服务器上start_register.php文件中监听的端口，也就是GatewayWorker启动时看到的Register的端口。
 * GatewayClient要想推送数据给客户端，必须知道客户端位于哪个GatewayWorker(集群)，
 * 然后去连这个GatewayWorker(集群)Register服务的 ip:端口，才能与对应GatewayWorker(集群)通讯。
 * 这个 ip:端口 在GatewayClient一侧使用 Gateway::$registerAddress 来指定。
 * 
 * === 如果GatewayClient和GatewayWorker不在同一台服务器需要以下步骤 ===
 * 1、需要设置start_gateway.php中的lanIp为实际的本机内网ip(如不在一个局域网也可以设置成外网ip)，设置完后要重启GatewayWorker
 * 2、GatewayClient这里的Gateway::$registerAddress的ip填写填写上面步骤1lanIp所指定的ip，端口
 * 3、需要开启GatewayWorker所在服务器的防火墙，让以下端口可以被GatewayClient所在服务器访问，
 *    端口包括Rgister服务的端口以及start_gateway.php中lanIp与startPort指定的几个端口
 *
 * === 如果GatewayClient和GatewayWorker在同一台服务器 ===
 * GatewayClient和Register服务都在一台服务器上，ip填写127.0.0.1及即可，无需其它设置。
 **/
Gateway::$registerAddress = '127.0.0.1:1236';

// GatewayClient支持GatewayWorker中的所有接口(Gateway::closeCurrentClient Gateway::sendToCurrentClient除外)
Gateway::sendToAll($data);
Gateway::sendToClient($client_id, $data);
Gateway::closeClient($client_id);
Gateway::isOnline($client_id);
Gateway::bindUid($client_id, $uid);
Gateway::isUidOnline($uid);
Gateway::getClientIdByUid($uid);
Gateway::unbindUid($client_id, $uid);
Gateway::sendToUid($uid, $dat);
Gateway::joinGroup($client_id, $group);
Gateway::sendToGroup($group, $data);
Gateway::leaveGroup($client_id, $group);
Gateway::getClientCountByGroup($group);
Gateway::getClientSessionsByGroup($group);
Gateway::getAllClientCount();
Gateway::getAllClientSessions();
Gateway::setSession($client_id, $session);
Gateway::updateSession($client_id, $session);
Gateway::getSession($client_id);
```

