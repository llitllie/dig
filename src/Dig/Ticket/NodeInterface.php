<?php

declare(strict_types=1);

namespace Dig\Ticket;

interface NodeInterface
{
    public const MAX_NODE_ID = 2 ** Number::NODE_BIT - 1;

    public function getId(): int;
}
