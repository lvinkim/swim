<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 8:59 PM
 */

namespace Lvinkim\Swim\Command\Server;


use Lvinkim\Swim\Kernel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerDevCommand extends Command
{
    private $kernel;

    public function __construct(Kernel $kernel)
    {
        parent::__construct();
        $this->kernel = $kernel;
    }

    public function configure()
    {
        $this->setName("server:dev:start")
            ->addArgument("port", InputArgument::OPTIONAL, "监听端口", "8080")
            ->setDescription("使用 php -S 运行服务");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $input->getArgument("port");
        $host = "0.0.0.0:{$port}";

        $projectDir = $this->kernel->getProjectDir();

        $output->writeln("正在监听: http://{$host}");

        exec("php -S {$host} -t {$projectDir}/public");
    }
}
