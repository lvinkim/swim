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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
            ->addArgument("port", InputArgument::OPTIONAL, "监听端口", 80)
            ->addOption("worker_num", "", InputOption::VALUE_REQUIRED, "worker 进程数")
            ->addOption("max_request", "", InputOption::VALUE_REQUIRED, "单个进程重启最大请求数")
            ->addOption("dispatch_mode", "", InputOption::VALUE_REQUIRED, "worker 调度模式")
            ->addOption("document_root", "", InputOption::VALUE_REQUIRED, "资源目录")
            ->setDescription("使用 swoole 运行服务");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $input->getArgument("port");

        $workerNum = $input->getOption("worker_num");
        $maxRequest = $input->getOption("max_request");
        $dispatchMode = $input->getOption("dispatch_mode");
        $documentRoot = $input->getOption("document_root");

        $this->kernel->boot();
        $container = $this->kernel->getContainer();

        $settings = ["port" => $port];
        $workerNum ? $settings["worker_num"] = $workerNum : null;
        $maxRequest ? $settings["max_request"] = $maxRequest : null;
        $dispatchMode ? $settings["dispatch_mode"] = $dispatchMode : null;
        $documentRoot ? $settings["document_root"] = $documentRoot : null;

        $httpServer = new HttpServer($container, $settings);
        $httpServer->run();

    }
}