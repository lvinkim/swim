<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 22/07/2018
 * Time: 10:47 AM
 */

namespace Lvinkim\Swim\Service\Component;


use Psr\Container\ContainerInterface;

interface ShareableServiceInterface
{
    /**
     * 必须注入容器
     * ShareableServiceInterface constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container);

    /**
     * 注册服务类
     * @param string $className
     * @return \Closure
     */
    public function register(string $className): \Closure;

    /**
     * 实例化已注册的服务
     * @param \Closure $closure
     * @return mixed
     */
    public function getInstance(\Closure $closure);
}