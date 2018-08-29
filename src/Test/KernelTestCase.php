<?php
/**
 * Created by PhpStorm.
 * User: bluehead
 * Date: 2018/8/29
 * Time: 下午9:04
 */

namespace Lvinkim\Swim\Test;


use Lvinkim\Swim\Kernel;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class KernelTestCase extends TestCase
{
    /** @var Kernel */
    protected static $kernel;

    /** @var ContainerInterface */
    protected static $container;

    protected static function bootKernel(array $settings=[])
    {
        static::$kernel = new Kernel($settings);
        static::$kernel->boot();

        static::$container = static::$kernel->getContainer();

        return static::$kernel;
    }
}