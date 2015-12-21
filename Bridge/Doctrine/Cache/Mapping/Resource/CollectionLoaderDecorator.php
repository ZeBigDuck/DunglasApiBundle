<?php

/*
 * This file is part of the DunglasApiBundle package.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dunglas\ApiBundle\Bridge\Doctrine\Cache\Mapping\Resource;

use Doctrine\Common\Cache\Cache;
use Dunglas\ApiBundle\Mapping\Resource\Loader\Collection\LoaderInterface;

/**
 * Cache decorator.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class LoaderDecorator implements LoaderInterface
{
    const KEY = 'rc';

    /**
     * @var LoaderInterface
     */
    private $loader;
    /**
     * @var Cache
     */
    private $cache;

    public function __construct(LoaderInterface $loader, Cache $cache)
    {
        $this->loader = $loader;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection()
    {
        if ($this->cache->contains(self::KEY)) {
            return $this->cache->fetch(self::KEY);
        }

        $collection = $this->loader->getCollection();
        $this->cache->save(self::KEY, $collection);

        return $collection;
    }
}
