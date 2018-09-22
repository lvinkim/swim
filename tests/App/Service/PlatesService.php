<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/23
 * Time: 10:22 PM
 */

namespace Tests\App\Service;


use League\Plates\Engine;
use Lvinkim\Swim\Service\Component\ShareableService;
use Psr\Container\ContainerInterface;
use Slim\Http\Response;

class PlatesService extends ShareableService
{
    /** @var Engine */
    private $engine;

    /** @var array 注册到模板的数组 */
    protected $renderData = [];

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $settings = $container->get("settings");
        $templatePath = $settings["plates"]["templatePath"];
        $fileExtension = $settings["plates"]["fileExtension"];

        $this->engine = new Engine($templatePath, $fileExtension);
    }

    public function assignRenderData($key, $val)
    {
        $this->renderData[$key] = $val;
    }

    public function render($name)
    {
        $router = $this->container->get('router');
        $request = $this->container->get('request');

        $this->assignRenderData('baseUrl', '/');
        $this->assignRenderData('router', $router);
        $this->assignRenderData('request', $request);

        $html = $this->engine->render($name, $this->renderData);

        /** @var Response $response */
        $response = $this->container->get("response");
        $response->withHeader("Content-Type", "text/html;charset=utf-8");
        $response->write($html);

        return $response;
    }

}