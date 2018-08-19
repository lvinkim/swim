<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 8:07 PM
 */

namespace Lvinkim\Swim\Console;


use Lvinkim\Swim\Command\Server\ServerDevCommand;
use Lvinkim\Swim\Command\Server\ServerReloadCommand;
use Lvinkim\Swim\Command\Server\ServerStartCommand;
use Lvinkim\Swim\Kernel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Console extends \Symfony\Component\Console\Application
{
    private $kernel;

    private $commandsRegistered = false;

    public function __construct(Kernel $kernel)
    {
        parent::__construct("Swim", Kernel::VERSION);
        $this->kernel = $kernel;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Throwable
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->registerCommands();

        return parent::doRun($input, $output);
    }

    protected function registerCommands()
    {
        if ($this->commandsRegistered) {
            return;
        }

        $this->addCommands([
            new ServerStartCommand($this->kernel),
            new ServerReloadCommand($this->kernel),
            new ServerDevCommand($this->kernel),
        ]);

    }
}