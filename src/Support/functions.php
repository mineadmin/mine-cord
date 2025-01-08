<?php
declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

use Hyperf\Context\ApplicationContext;
use Psr\Container\ContainerInterface;
use Hyperf\Context\Context;

if (! function_exists('container')) {
    /**
     * 获取容器实例.
     * @deprecated 3.0 , Use di() instead.
     */
    function container(): ContainerInterface
    {
        return ApplicationContext::getContainer();
    }
}


if (! function_exists('di')) {
    /**
     * 获取容器实例.
     */
    function di(): ContainerInterface
    {
        return ApplicationContext::getContainer();
    }
}


if (! function_exists('context_set')) {
    /**
     * 设置上下文数据.
     * @param mixed $data
     */
    function context_set(string $key, $data): bool
    {
        return (bool) Context::set($key, $data);
    }
}

if (! function_exists('context_get')) {
    /**
     * 获取上下文数据.
     * @return mixed
     */
    function context_get(string $key)
    {
        return Context::get($key);
    }
}