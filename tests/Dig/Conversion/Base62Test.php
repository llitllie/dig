<?php

declare(strict_types=1);

namespace Tests\Dig\Conversion;

use PHPUnit\Framework\TestCase;
use Dig\Conversion\Base62;

class Base62Test extends TestCase
{
    public function testTo62(): void
    {
        $converter = new Base62();
        $this->assertEquals('0', $converter->encode(0));
        $this->assertEquals('a', $converter->encode(10));
        $this->assertEquals('A', $converter->encode(36));
        $this->assertEquals('1Z', $converter->encode(123));
        $this->assertEquals('20', $converter->encode(124));
        $this->assertEquals('21', $converter->encode(125));
        $this->assertEquals('mdTlMDC', $converter->encode(1262332800000));
        $this->assertEquals('1vNjNOuoPU4', $converter->encode(1269717454121082880));
        $this->assertEquals('1vNjNOuoPW4', $converter->encode(1269717454121083004));
        $this->assertEquals('hBxM5A4', $converter->encode(1000000000000));
        $this->assertEquals('ZZZZZZZ', $converter->encode(3521614606207));
    }

    public function testTo10(): void
    {
        $converter = new Base62();
        $this->assertEquals(0, $converter->decode('0'));
        $this->assertEquals(10, $converter->decode('a'));
        $this->assertEquals(36, $converter->decode('A'));
        $this->assertEquals(123, $converter->decode('1Z'));
        $this->assertEquals(124, $converter->decode('20'));
        $this->assertEquals(1269717454121082880, $converter->decode('1vNjNOuoPU4'));
        $this->assertEquals(1269717454121083004, $converter->decode('1vNjNOuoPW4'));
        $this->assertEquals(1000000000000, $converter->decode('hBxM5A4'));
        $this->assertEquals(3521614606207, $converter->decode('ZZZZZZZ'));
    }
}
