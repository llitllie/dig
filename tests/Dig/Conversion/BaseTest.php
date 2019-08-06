<?php

declare(strict_types=1);

namespace Tests\Dig\Conversion;

use PHPUnit\Framework\TestCase;
use Dig\Conversion\Base;

class BaseTest extends TestCase
{
    public function testToN(): void
    {
        $converter2 = new Base(2, '01');
        $this->assertEquals('0', $converter2->encode(0));
        $this->assertEquals('1', $converter2->encode(1));
        $this->assertEquals('10', $converter2->encode(2));
        $this->assertEquals('11', $converter2->encode(3));
        $this->assertEquals('100', $converter2->encode(4));
        $this->assertEquals('101', $converter2->encode(5));
        $converter8 = new Base(8, '01234567');
        $this->assertEquals('0', $converter8->encode(0));
        $this->assertEquals('1', $converter8->encode(1));
        $this->assertEquals('10', $converter8->encode(8));
        $this->assertEquals('12', $converter8->encode(10));
        $this->assertEquals('24', $converter8->encode(20));
        $converter16 = new Base(16, '0123456789abcdef');
        $this->assertEquals('0', $converter16->encode(0));
        $this->assertEquals('1', $converter16->encode(1));
        $this->assertEquals('8', $converter16->encode(8));
        $this->assertEquals('a', $converter16->encode(10));
        $this->assertEquals('14', $converter16->encode(20));
        $converter62 = new Base(62, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $this->assertEquals('mdTlMDC', $converter62->encode(1262332800000));
        $this->assertEquals('1vNjNOuoPU4', $converter62->encode(1269717454121082880));
        $this->assertEquals('1vNjNOuoPW4', $converter62->encode(1269717454121083004));
        $this->assertEquals('hBxM5A4', $converter62->encode(1000000000000));
        $this->assertEquals('ZZZZZZZ', $converter62->encode(3521614606207));
    }

    public function testTo10(): void
    {
        $converter2 = new Base(2, '01');
        $this->assertEquals(0, $converter2->decode('0'));
        $this->assertEquals(1, $converter2->decode('1'));
        $this->assertEquals(2, $converter2->decode('10'));
        $this->assertEquals(3, $converter2->decode('11'));
        $this->assertEquals(4, $converter2->decode('100'));
        $this->assertEquals(5, $converter2->decode('101'));
        $converter8 = new Base(8, '01234567');
        $this->assertEquals(0, $converter8->decode('0'));
        $this->assertEquals(1, $converter8->decode('1'));
        $this->assertEquals(8, $converter8->decode('10'));
        $this->assertEquals(10, $converter8->decode('12'));
        $this->assertEquals(20, $converter8->decode('24'));
        $converter16 = new Base(16, '0123456789abcdef');
        $this->assertEquals(0, $converter16->decode('0'));
        $this->assertEquals(1, $converter16->decode('1'));
        $this->assertEquals(8, $converter16->decode('8'));
        $this->assertEquals(10, $converter16->decode('a'));
        $this->assertEquals(20, $converter16->decode('14'));
        $converter62 = new Base(62, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $this->assertEquals(1269717454121082880, $converter62->decode('1vNjNOuoPU4'));
        $this->assertEquals(1269717454121083004, $converter62->decode('1vNjNOuoPW4'));
        $this->assertEquals(1000000000000, $converter62->decode('hBxM5A4'));
        $this->assertEquals(3521614606207, $converter62->decode('ZZZZZZZ'));
    }
}
