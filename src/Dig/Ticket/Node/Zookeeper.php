<?php

declare(strict_types=1);

namespace Dig\Ticket\Node;

use Dig\Ticket\Exception\UnavailableNodeIdException;
use Dig\Ticket\NodeInterface;
use Dig\Zookeeper\Client;

class Zookeeper implements NodeInterface
{
    private $zk;
    private $dsn;
    private $pool;
    private $basePath = '/dig/ticket';
    private $acls = [
        [
            'perms' => \Zookeeper::PERM_ALL,
            'scheme' => 'world',
            'id' => 'anyone',
        ],
    ];
    private $id;

    public function __construct(string $dsn, string $path = '/sim/ticket')
    {
        $this->dsn = $dsn;
        $this->pool = new \SplQueue();
        if (!empty($path)) {
            $this->basePath = $path;
        }
    }

    public function getZookeeper(): Client
    {
        if (null === $this->zk) {
            $this->zk = new Client($this->dsn);
        }

        return $this->zk;
    }

    public function getId(): int
    {
        if (null === $this->id) {
            if (!$this->getZookeeper()->exists($this->basePath)) {
                $this->getZookeeper()->makePath($this->basePath);
            }
            $i = 1;
            $length = \mb_strlen((string) self::MAX_NODE_ID);
            $nodeId = \sprintf('%0'.$length.'d', $i);
            $children = $this->getZookeeper()->getChildren($this->basePath);
            $children = empty($children) ? [] : $children;
            for (; $i <= self::MAX_NODE_ID; ++$i) {
                $nodeId = \sprintf('%0'.$length.'d', $i);
                if (!\in_array($nodeId, $children)) {
                    $path = $this->basePath.'/'.$nodeId;
                    if ($this->getZookeeper()->exists($path)) {
                        //throw new UnavailableNodeIdException('node already exist: '.$path);
                        continue;
                    }
                    try {
                        $this->getZookeeper()->makeNode($path, $nodeId, $this->acls, \Zookeeper::EPHEMERAL);
                        break;
                    } catch (\ZookeeperException $e) {
                        //throw new UnavailableNodeIdException('cannot create node in zookeeper: '.$e->getMessage());
                        continue;
                    }
                }
            }
            if (self::MAX_NODE_ID === $i) {
                throw new UnavailableNodeIdException('cannot create node in zookeeper: reach max node limit '.self::MAX_NODE_ID);
            }
            $this->id = $i;
        }

        return $this->id;
    }
}
