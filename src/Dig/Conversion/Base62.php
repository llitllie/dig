<?php

declare(strict_types=1);

namespace Dig\Conversion;


class Base62 extends Base
{
    public function __construct()
    {
        parent::__construct(62, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
    }
}
