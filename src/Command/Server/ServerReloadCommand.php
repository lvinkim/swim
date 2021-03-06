<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 4:51 PM
 */

namespace Lvinkim\Swim\Command\Server;


use Lvinkim\Swim\Kernel;
use Lvinkim\Swim\Server\HttpShare;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerReloadCommand extends Command
{
    private $kernel;

    public function __construct(Kernel $kernel)
    {
        parent::__construct();
        $this->kernel = $kernel;
    }

    public function configure()
    {
        $this->setName("server:reload")
            ->setDescription("重载 swoole 服务");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $managerPid = HttpShare::getManagerPid();
        if ($managerPid > 0) {
            posix_kill($managerPid, SIGUSR1);
            $output->writeln("重载服务完成");
        } else {
            $output->writeln("未检测到运行的服务");
        }
    }
}