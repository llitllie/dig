<?php
include __DIR__.'/../vendor/autoload.php';
use Dig\Ticket\Number;
use Dig\Ticket\Node\Zookeeper as ZookeeperNode;


/** 
 * swoole - zookeeper tick dispatch issue: https://github.com/php-zookeeper/php-zookeeper
*/
$host = getenv("ZOOKEEPER_CONNECTION");
$host = empty($host) ? "192.168.33.1:2181" : $host;
$node = new ZookeeperNode($host);

$http = new \Swoole\Http\Server("0.0.0.0", 9501);

$http->on("start", function ($server) {
    echo "Swoole http server is started at http://0.0.0.0:9501\n";
});

$http->on("WorkerStart", function ($server, $workerId) use($node) {
    // https://wiki.swoole.com/wiki/page/325.html
    // https://wiki.swoole.com/wiki/page/852.html
    // https://wiki.swoole.com/wiki/page/865.html
    // use lazy initial zk here, so that each worker can hold its own zk resource
    // if we only run swoole http server in 1 worker process (1 CPU), then no need to consider this
    $id = $node->getId();
    $server->nodeId = $id;
    $server->number = new Number($server->nodeId);
});

$http->on("request", function ($request, $response) use ($http) {
    $data = $http->number->generate();
    $response->end($data);
});

$http->start();
