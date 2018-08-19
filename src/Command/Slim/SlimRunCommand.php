<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 11:05 PM
 */

namespace Lvinkim\Swim\Command\Slim;


use Slim\App;
use Slim\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SlimRunCommand extends Command
{
    private $container;

    public function __construct(Container $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    public function configure()
    {
        $this->setName("slim:run")
            ->setDescription("运行 slim 框架");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var App $app */
        $app = $this->container->raw(App::class);
        $app->run();
    }
}