<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 4:51 PM
 */

namespace Lvinkim\Swim\Command\Server;


use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerReloadCommand extends Command
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    public function configure()
    {
        $this->setName("server:reload")
            ->setDescription("重载服务");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("重载服务成功");
    }
}