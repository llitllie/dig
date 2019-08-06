<?php

declare(strict_types=1);

namespace Tests\Dig\Conversion;

use PHPUnit\Framework\TestCase;
use Dig\Conversion\Base36;

class Base36Test extends TestCase
{
    public function testTo62(): void
    {
        $converter = new Base36();
        $this->assertEquals('0', $converter->encode(0));
        $this->assertEquals('a', $converter->encode(10));
        $this->assertEquals('10', $converter->encode(36));
        $this->assertEquals('3f', $converter->encode(123));
        $this->assertEquals('3g', $converter->encode(124));
        $this->assertEquals('3h', $converter->encode(125));
        $this->assertEquals('g3wocu80', $converter->encode(1262332800000));
        $this->assertEquals('9na59tveitj4', $converter->encode(1269717454121082880));
        $this->assertEquals('9na59tveitmk', $converter->encode(1269717454121083004));
        $this->assertEquals('cre66i9s', $converter->encode(1000000000000));
        $this->assertEquals('18xt2esu7', $converter->encode(3521614606207));
    }

    public function testTo10(): void
    {
        $converter = new Base36();
        $this->assertEquals(0, $converter->decode('0'));
        $this->assertEquals(10, $converter->decode('a'));
        $this->assertEquals(36, $converter->decode('10'));
        $this->assertEquals(123, $converter->decode('3f'));
        $this->assertEquals(124, $converter->decode('3g'));
        $this->assertEquals(1269717454121082880, $converter->decode('9na59tveitj4'));
        $this->assertEquals(1269717454121083004, $converter->decode('9na59tveitmk'));
        $this->assertEquals(1000000000000, $converter->decode('cre66i9s'));
        $this->assertEquals(3521614606207, $converter->decode('18xt2esu7'));
    }
}
