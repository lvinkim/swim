<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 3:22 PM
 */

namespace Lvinkim\Swim;


use Lvinkim\Swim\Bundle\Bundle;
use Lvinkim\Swim\Bundle\SwimBundle;
use Slim\App;
use Slim\Container;

class Kernel
{
    const VERSION = "0.1.0";

    protected $settings;

    /** @var Container */
    protected $container;
    protected $rootDir;
    protected $environment;
    protected $debug;
    protected $booted = false;
    protected $name;

    private $projectDir;

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
        $this->projectDir = $settings['projectDir'] ?? $this->rootDir;
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

        $dependencies = $this->settings["dependencies"] ?? "";
        $middleware = $this->settings["middleware"] ?? "";
        $routes = $this->settings["routes"] ?? "";

        $requires = [$dependencies, $middleware, $routes];
        foreach ($requires as $require) {
            if (is_file($require)) {
                require $require . "";
            }
        }

        $this->container = $app->getContainer();
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
        yield new SwimBundle();
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

    /**
     * @return mixed|string
     */
    public function getProjectDir()
    {
        return $this->projectDir;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }
}