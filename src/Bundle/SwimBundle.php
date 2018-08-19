<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 10:41 PM
 */

namespace Lvinkim\Swim\Bundle;

use Lvinkim\Swim\Command\Slim\SlimRunCommand;
use Lvinkim\Swim\Handler\ErrorHandler;
use Lvinkim\Swim\Handler\NotAllowedHandler;
use Lvinkim\Swim\Handler\NotFoundHandler;
use Lvinkim\Swim\Handler\PhpErrorHandler;
use Lvinkim\Swim\Service\AutoRegister;
use Lvinkim\Swim\Service\Logger\CustomLogger;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

class SwimBundle extends Bundle
{
    private $container;

    public function boot()
    {
        $autoRegister = new AutoRegister($this->container);

        $autoRegister->register([
            CustomLogger::class,
            'errorHandler' => ErrorHandler::class, // 抛出了未处理的异常
            'phpErrorHandler' => PhpErrorHandler::class, // PHP 运行时错误 throwable
            'notAllowedHandler' => NotAllowedHandler::class, // 错误的 Http 请求 Method 405
            'notFoundHandler' => NotFoundHandler::class, // 请求的路由不存在 404
        ]);
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
            new SlimRunCommand($this->container),
        ]);
    }
}