<?php

declare(strict_types=1);

namespace Dig\Ticket;

use Dig\Ticket\Exception\IllegalTimeException;

class Number
{
    public const TOTAL_BIT = 64;
    public const EPOCH_BIT = 42;
    public const NODE_BIT = 10;
    public const SEQUENCE_BIT = 12;

    public const MAX_NODE_ID = 2 ** self::NODE_BIT - 1;
    public const MAX_SEQUENCE_NUMBER = 2 ** self::SEQUENCE_BIT - 1;
    public const CUSTOM_EPOCH = 1262332800000;

    private $lastTimestamp = 0;
    private $sequence = 0;

    private $nodeId = 0;

    public function __construct(int $nodeId)
    {
        $this->nodeId = $nodeId;
    }

    public function getNodeId(): int
    {
        return $this->nodeId;
    }

    public function getTimestamp(): int
    {
        return (int) (\microtime(true) * 1000) - self::CUSTOM_EPOCH;
    }

    public function generate(): int
    {
        $current = $this->getTimestamp();
        if ($current < $this->lastTimestamp) {
            throw new IllegalTimeException('current timestamp cannot less than before');
        }
        if ($current === $this->lastTimestamp) {
            $this->sequence = ($this->sequence + 1) & self::MAX_SEQUENCE_NUMBER;
            if (0 === $this->sequence) {
                $current = $this->_waitNextTimestamp($current);
            }
        } else {
            $this->sequence = 0;
        }
        $this->lastTimestamp = $current;
        $id = $current << (self::TOTAL_BIT - self::EPOCH_BIT);
        $id = $id | ($this->getNodeId() << (self::TOTAL_BIT - self::EPOCH_BIT - self::NODE_BIT));
        $id = $id | $this->sequence;

        return $id;
    }

    private function _waitNextTimestamp($current)
    {
        while ($current === $this->lastTimestamp) {
            $current = $this->getTimestamp();
        }

        return $current;
    }
}
