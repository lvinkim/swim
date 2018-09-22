<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/24
 * Time: 12:44 AM
 */

namespace Tests\App\Bundle;

use HaydenPierce\ClassFinder\ClassFinder;
use Lvinkim\Swim\Bundle\Bundle;
use Lvinkim\Swim\Middleware\AccessCostMiddleware;
use Lvinkim\Swim\Service\AutoRegister;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Container;
use Symfony\Component\Console\Application;

class AppBundle extends Bundle
{
    private $container;

    public function boot()
    {
        /** @var Container $container */
        $container = $this->container;

        $autoRegister = new AutoRegister($container);

        $serviceClasses = $this->getAllServiceClasses();

        $autoRegister->register($serviceClasses);
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
        $commandClasses = $this->getALlCommandClasses();

        $commandObjects = [];
        foreach ($commandClasses as $commandClass) {
            $commandObjects[] = new $commandClass($this->container);
        }

        $application->addCommands($commandObjects);
    }

    private function getALlCommandClasses()
    {
        try {
            $classes = ClassFinder::getClassesInNamespace("Tests\App\Command");
        } catch (\Exception $exception) {
            $classes = [];
        }
        return $classes;
    }

    /**
     * @return array
     */
    private function getAllServiceClasses()
    {
        try {
            $classes = ClassFinder::getClassesInNamespace("Tests\App\Service");
        } catch (\Exception $exception) {
            $classes = [];
        }
        return $classes;

    }
}