<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 3:22 PM
 */

namespace Lvinkim\Swim;


use Lvinkim\Swim\Bundle\Bundle;
use Lvinkim\Swim\Bundle\ServerBundle;
use Slim\App;

class Kernel
{
    const VERSION = "0.1.0";

    protected $settings;

    protected $container;
    protected $rootDir;
    protected $environment;
    protected $debug;
    protected $booted = false;
    protected $name;

    protected $bundlesConfig;

    /** @var Bundle[] */
    protected $bundles = [];

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
        $this->environment = $settings['env'] ?? 'prod';
        $this->debug = $settings['debug'] ?? false;
        $this->bundlesConfig = $settings['bundles'] ?? false;
        $this->rootDir = $this->getRootDir();
        $this->name = $this->getName();
    }

    public function boot()
    {
        if (true === $this->booted) {
            return;
        }

        $this->initializeBundles();

        $this->initializeContainer();

        foreach ($this->getBundles() as $bundle) {
            $bundle->setContainer($this->container);
            $bundle->boot();
        }

        $this->booted = true;
    }

    /**
     * @return Bundle[]
     */
    public function getBundles(): array
    {
        return $this->bundles;
    }

    protected function initializeContainer()
    {
        $app = new App(['settings' => $this->settings]);
        $this->container = $app->getContainer();

        $this->container[Kernel::class] = $this;
        $this->container[App::class] = $app;
    }

    protected function initializeBundles()
    {
        $this->bundles = [];
        foreach ($this->registerBundles() as $bundle) {
            $name = $bundle->getName();
            if (isset($this->bundles[$name])) {
                throw new \LogicException(sprintf('Trying to register two bundles with the same name "%s"', $name));
            }
            $this->bundles[$name] = $bundle;
        }
    }

    /**
     * @return \Generator
     */
    protected function registerBundles()
    {
        yield new ServerBundle();
        if (is_file($this->bundlesConfig)) {
            $contents = require strval($this->bundlesConfig) . "";
            foreach ($contents as $class => $envs) {
                if (isset($envs['all']) || isset($envs[$this->environment])) {
                    yield new $class();
                }
            }
        }
    }

    public function getRootDir()
    {
        if (null === $this->rootDir) {
            $r = new \ReflectionObject($this);
            $this->rootDir = dirname($r->getFileName());
        }

        return $this->rootDir;
    }

    public function getName()
    {
        if (null === $this->name) {
            $this->name = preg_replace('/[^a-zA-Z0-9_]+/', '', basename($this->rootDir));
            if (ctype_digit($this->name[0])) {
                $this->name = '_' . $this->name;
            }
        }

        return $this->name;
    }
}