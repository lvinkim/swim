<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 3:32 PM
 */

namespace Lvinkim\Swim\Command\Server;


use Lvinkim\Swim\Kernel;
use Lvinkim\Swim\Server\HttpServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerStartCommand extends Command
{
    private $kernel;

    public function __construct(Kernel $kernel)
    {
        parent::__construct();
        $this->kernel = $kernel;
    }

    public function configure()
    {
        $this->setName("server:start")
            ->setDescription("使用 swoole 运行服务");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->kernel->boot();
        $container = $this->kernel->getContainer();

        $httpServer = new HttpServer($container);
        $httpServer->run();

    }
}