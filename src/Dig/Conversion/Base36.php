<?php

declare(strict_types=1);

namespace Dig\Conversion;


class Base36 extends Base
{
    public function __construct()
    {
        //parent::__construct(36, '0123456789abcdefghijklmnopqrstuvwxyz');
    }

    public function encode(int $number): string
    {
        return base_convert((string) $number, 10, 36);
    }
    
    public function decode(string $number): int
    {
        return (int) base_convert($number, 36, 10);
    }
}
