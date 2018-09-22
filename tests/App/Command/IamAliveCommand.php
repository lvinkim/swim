<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/14
 * Time: 9:45 PM
 */

namespace Tests\App\Command;


use Lvinkim\Swim\Service\Logger\CustomLogger;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IamAliveCommand extends Command
{
    /** @var CustomLogger */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->logger = $container->get(CustomLogger::class);
    }

    protected function configure()
    {
        $this->setName('zone:app:alive')
            ->setDescription('上报心跳');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->visionWatch('command', 'i-am-alive', 1);
    }

}