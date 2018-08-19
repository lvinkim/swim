<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/3
 * Time: 6:15 PM
 */

namespace Lvinkim\Swim\Handler;

use Lvinkim\Swim\Service\Logger\CustomLogger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ErrorHandler
{
    /** @var CustomLogger */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container[CustomLogger::class];
    }

    public function __invoke(Request $request, Response $response, \Exception $exception)
    {
        $this->logger->log('error', ['error' => $exception->getMessage()], 'error');

        return $response->withStatus(500)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write(json_encode([
                'success' => false,
                'message' => '500 Error',
                'data' => [
                    'error' => $exception->getMessage(),
                ]
            ]));
    }
}