<?php

declare(strict_types=1);

namespace Tests\Dig\Zookeeper;

use Dig\Zookeeper\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testZookeeper(): void
    {
        $zk = new Client(\getenv('ZOOKEEPER_CONNECTION'));
        $this->assertEquals(\Zookeeper::CONNECTING_STATE, $zk->getState());
        $path = '/ticket';
        $value = '100';
        $result = $zk->exists($path);
        $this->assertFalse($result);
        if (!$result) {
            $acl = [
                [
                  'perms' => \Zookeeper::PERM_ALL,
                  'scheme' => 'world',
                  'id' => 'anyone',
                ],
            ];
            $type = \Zookeeper::EPHEMERAL;
            $result = $zk->create($path, $value, $acl, $type);
            $this->assertNotFalse($result);
        }
        $result = $zk->set($path, $value);
        $this->assertTrue($result);
        $data = $zk->get($path);
        $this->assertEquals($value, $data);
        $children = $zk->getChildren($path);
        $this->assertIsArray($children);
        $result = $zk->delete($path);
        $this->assertTrue($result);
    }

    public function testZookeeperExtension(): void
    {
        $zk = new Client(\getenv('ZOOKEEPER_CONNECTION'));
        $path = '/tests/sim/ticket';
        if ($zk->exists($path)) {
            $this->assertTrue($zk->deletePath($path));
        }
        $this->assertTrue($zk->makePath($path));
        $this->assertTrue($zk->makeNode($path.'/test', 'test'));
        $this->assertTrue($zk->deletePath($path));
    }
}
