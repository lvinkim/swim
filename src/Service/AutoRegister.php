<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 22/07/2018
 * Time: 7:26 PM
 */

namespace Lvinkim\Swim\Service;


use Psr\Container\ContainerInterface;

/**
 * 自动注册服务到容器中
 * Class AutoRegister
 * @package App\Service
 */
class AutoRegister
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $classes
     */
    public function register(array $classes = [])
    {
        foreach ($classes as $id => $className) {
            if (is_numeric($id)) {
                $id = $className;
            }
            $this->container[$id] = function (ContainerInterface $c) use ($className) {
                return new $className($c);
            };
        }
    }

    /**
     * @param $objects string|array|\object|mixed
     */
    public function bind($objects)
    {
        foreach ($objects as $id => $object) {
            if (is_string($object)) {
                $id = $object;
                $className = $object;
                $object = new $className($this->container);
            }

            $this->container[$id] = $object;
        }
    }
}