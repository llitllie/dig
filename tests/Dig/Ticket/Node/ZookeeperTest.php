<?php

declare(strict_types=1);

namespace Tests\Dig\Ticket\Node;

use Dig\Ticket\Node\Zookeeper;
use PHPUnit\Framework\TestCase;

class ZookeeperTest extends TestCase
{
    public function testGetId(): void
    {
        $node = new Zookeeper(\getenv('ZOOKEEPER_CONNECTION'));
        $id = $node->getId();
        $this->assertIsInt($id);
        $this->assertLessThanOrEqual(Zookeeper::MAX_NODE_ID, $id);
    }
}
