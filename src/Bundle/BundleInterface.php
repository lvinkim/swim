<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 3:46 PM
 */

namespace Lvinkim\Swim\Bundle;


use Psr\Container\ContainerInterface;

interface BundleInterface
{
    public function boot();

    public function setContainer(ContainerInterface $container = null);

    public function getName();
}