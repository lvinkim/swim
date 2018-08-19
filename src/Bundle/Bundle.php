<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 3:47 PM
 */

namespace Lvinkim\Swim\Bundle;


use Symfony\Component\Console\Application;

abstract class Bundle implements BundleInterface
{
    public function registerCommands(Application $application)
    {

    }
}