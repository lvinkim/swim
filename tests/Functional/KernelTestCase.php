<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/9/2
 * Time: 6:07 PM
 */

namespace Tests\Functional;

use Lvinkim\Swim\Kernel;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class KernelTestCase extends TestCase
{
    /** @var Kernel */
    protected static $kernel;

    /** @var ContainerInterface */
    protected static $container;

    protected static function bootKernel(array $settings = [])
    {
        static::$kernel = new Kernel($settings);
        static::$kernel->boot();

        static::$container = static::$kernel->getContainer();

        return static::$kernel;
    }
}