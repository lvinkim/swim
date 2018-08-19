<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/3
 * Time: 7:29 PM
 */

namespace Lvinkim\Swim\Handler;


use Lvinkim\Swim\Service\Logger\CustomLogger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class NotFoundHandler
{
    /** @var CustomLogger */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container[CustomLogger::class];
    }

    public function __invoke(Request $request, Response $response)
    {
        $this->logger->log('not-found', ['uri' => $request->getUri()], 'error');

        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write(json_encode([
                'success' => false,
                'message' => '404 Not Found',
                'data' => []
            ]));
    }
}