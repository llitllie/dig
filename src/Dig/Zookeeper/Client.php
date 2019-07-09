<?php

declare(strict_types=1);

namespace Dig\Zookeeper;

class Client extends \Zookeeper
{
    public function makePath(string $path, string $value = ''): bool
    {
        $arrPath = \explode('/', $path);
        if (!empty($arrPath)) {
            $arrPath = \array_filter($arrPath);
            $subpath = '';
            $flag = true;
            foreach ($arrPath as $p) {
                $subpath .= '/'.$p;
                if (!$this->exists($subpath)) {
                    if (!$this->makeNode($subpath, $value)) {
                        $flag = false;
                        break;
                    }
                }
            }

            return $flag;
        }

        return false;
    }

    public function makeNode(string $path, string $value, array $acls = [], int $flag = 0): bool
    {
        if (empty($acls)) {
            $acls = [
                [
                    'perms' => \Zookeeper::PERM_ALL,
                    'scheme' => 'world',
                    'id' => 'anyone',
                ],
            ];
        }
        if ($this->create($path, $value, $acls, $flag)) {
            return true;
        }

        return false;
    }

    public function deletePath(string $path): bool
    {
        $children = $this->getChildren($path);
        if (!empty($children)) {
            foreach ($children as $child) {
                $subpath = $path.'/'.$child;
                $this->deletePath($subpath);
            }
        }

        return $this->delete($path);
    }
}
