<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 3:32 PM
 */

namespace Lvinkim\Swim\Command\Server;


use Lvinkim\Swim\Service\HttpService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerStartCommand extends Command
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    public function configure()
    {
        $this->setName("server:start")
            ->setDescription("启动服务");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $httpService = new HttpService($this->container);
        $httpService->run();
    }
}