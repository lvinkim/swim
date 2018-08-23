<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/7/24
 * Time: 2:11 PM
 */

namespace Lvinkim\Swim\Middleware;


use Lvinkim\Swim\Service\Logger\CustomLogger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Request;


class AccessCostMiddleware
{
    /** @var CustomLogger */
    private $logger;

    private $marker = [];


    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container[CustomLogger::class];
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        /** @var Request $request */
        $path = $request->getUri()->getPath();
        $content = [
            'ip' => $request->getServerParam('REMOTE_ADDR'),
            'path' => $path,
        ];
        $this->logger->log('access', $content, 'access');

        $this->mark('access-cost-begin');

        $nextResponse = $next($request, $response);

        $this->mark('access-cost-end');

        try {

            /** @var  $request Request */
            $elapsedTime = $this->elapsedTime('access-cost-begin', 'access-cost-end');;
            $content['elapsed'] = $elapsedTime;

            $this->logger->log('cost', $content, 'access');

        } catch (\Error $e) {

        } catch (\Exception $e) {

        }

        return $nextResponse;
    }

    private function mark($name)
    {
        $this->marker[$name] = microtime();
    }

    private function elapsedTime($point1 = '', $point2 = '', $decimals = 4)
    {
        if ($point1 == '') {
            return '{elapsed_time}';
        }

        if (!isset($this->marker[$point1])) {
            return '';
        }

        if (!isset($this->marker[$point2])) {
            $this->marker[$point2] = microtime();
        }

        list($sm, $ss) = explode(' ', $this->marker[$point1]);
        list($em, $es) = explode(' ', $this->marker[$point2]);

        $elapsedTime = number_format(($em + $es) - ($sm + $ss), $decimals);
        $elapsedTime = round(floatval($elapsedTime), 4);
        return $elapsedTime;
    }
}