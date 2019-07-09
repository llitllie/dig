# Distribute ticket ID generator in Swoole HTTP server toggether with Zookeeper.

##### Install
```sh
composer require llitllie/dig
```
Requires PHP 7.2 toggether with Swoole and Zookeeper extensions.
##### Usage
```php
<?php
include __DIR__.'/../vendor/autoload.php';
use Dig\Ticket\Number;
use Dig\Ticket\Node\Zookeeper as ZookeeperNode;

$host = getenv("ZOOKEEPER_CONNECTION");
$host = empty($host) ? "192.168.33.1:2181" : $host;
$node = new ZookeeperNode($host);
$nodeId = $node->getId();
echo $nodeId.PHP_EOL;
$number = new Number($nodeId);
$ticket = $number->generate();
echo $ticket.PHP_EOL;
```
