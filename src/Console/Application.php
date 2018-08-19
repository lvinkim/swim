<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 3:19 PM
 */

namespace Lvinkim\Swim\Console;


use Lvinkim\Swim\Bundle\Bundle;
use Lvinkim\Swim\Kernel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends \Symfony\Component\Console\Application
{

    private $kernel;

    private $commandsRegistered = false;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;

        parent::__construct('Swim', Kernel::VERSION);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Throwable
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->kernel->boot();

        $this->registerCommands();

        return parent::doRun($input, $output);
    }

    protected function registerCommands()
    {
        if ($this->commandsRegistered) {
            return;
        }

        $this->commandsRegistered = true;

        $this->kernel->boot();

        foreach ($this->kernel->getBundles() as $bundle) {
            if ($bundle instanceof Bundle) {
                try {
                    $bundle->registerCommands($this);
                } catch (\Exception $e) {
                    null;
                } catch (\Throwable $e) {
                    null;
                }
            }
        }
    }

    /**
     * @return Kernel
     */
    public function getKernel(): Kernel
    {
        return $this->kernel;
    }
}