<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 4:38 PM
 */

namespace Lvinkim\Swim\Bundle;


use Lvinkim\Swim\Command\Server\ServerReloadCommand;
use Lvinkim\Swim\Command\Server\ServerStartCommand;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

class ServerBundle extends Bundle
{
    private $container;

    public function boot()
    {
        // noop
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getName()
    {
        return self::class;
    }

    public function registerCommands(Application $application)
    {
        $application->addCommands([
            new ServerStartCommand($this->container),
            new ServerReloadCommand($this->container),
        ]);
    }
}