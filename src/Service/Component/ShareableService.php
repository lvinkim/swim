<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 22/07/2018
 * Time: 10:52 AM
 */

namespace Lvinkim\Swim\Service\Component;


use Psr\Container\ContainerInterface;

class ShareableService implements ShareableServiceInterface
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * 必须注入容器
     * ShareableServiceInterface constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * 注册服务类
     * @param string $className
     * @return \Closure
     */
    public function register(string $className): \Closure
    {
        return (function (ContainerInterface $container) use ($className) {
            return $container[$className];
        });
    }

    /**
     * 实例化已注册的服务
     * @param \Closure $closure
     * @return mixed
     */
    public function getInstance(\Closure $closure)
    {
        return call_user_func($closure, $this->container);
    }
}