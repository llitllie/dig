<?php

declare(strict_types=1);

namespace Tests\Dig\Ticket;

use Dig\Ticket\Node\Zookeeper;
use Dig\Ticket\Number;
use PHPUnit\Framework\TestCase;

class NumberTest extends TestCase
{
    public function testLoad(): void
    {
        $this->assertEquals(64, Number::TOTAL_BIT);
        $this->assertEquals(1023, Number::MAX_NODE_ID);
        $this->assertEquals(4095, Number::MAX_SEQUENCE_NUMBER);
    }

    public function testGetTimestamp(): void
    {
        $number = new Number(0);
        $past = (int) (\microtime(true) * 1000) - Number::CUSTOM_EPOCH;
        $timestamp = $number->getTimestamp();
        $this->assertIsInt($timestamp);
        $this->assertGreaterThanOrEqual($past, $timestamp);
    }

    public function testGetNodeId(): void
    {
        $nodeId = 1;
        $number = new Number($nodeId);
        $this->assertEquals($nodeId, $number->getNodeId());
    }

    public function testGenerate(): void
    {
        $number = new Number(0);
        $first = $number->generate();
        $this->assertIsInt($first);
        $second = $number->generate();
        $this->assertGreaterThan($first, $second);
    }

    public function testGenerateWithZookeeperNode(): void
    {
        $node = new Zookeeper(\getenv('ZOOKEEPER_CONNECTION'));
        $id = $node->getId();
        $number = new Number($id);
        $this->assertIsInt($number->generate());
    }
}
