<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/24
 * Time: 12:44 AM
 */

namespace Tests\App\Bundle;

use Lvinkim\Swim\Bundle\Bundle;
use Lvinkim\Swim\Middleware\AccessCostMiddleware;
use Lvinkim\Swim\Service\AutoRegister;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Container;
use Symfony\Component\Console\Application;
use Tests\App\Service\PlatesService;

class AppBundle extends Bundle
{
    private $container;

    public function boot()
    {
        /** @var Container $container */
        $container = $this->container;

        $autoRegister = new AutoRegister($container);

        $autoRegister->register([
            PlatesService::class,
        ]);
        // ... register more services

        /** @var App $app */
        $app = $container->raw(App::class);
        $app->add(new AccessCostMiddleware($container));
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
            // ... more commands
        ]);
    }
}