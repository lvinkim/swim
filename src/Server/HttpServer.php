<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 5:06 PM
 */

namespace Lvinkim\Swim\Server;

use Slim\App;
use Slim\Container;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Server;

class HttpServer
{
    /** @var App */
    private $app;
    private $container;

    protected $server;
    protected $host;
    protected $port;
    protected $workerNum;
    protected $maxRequest;
    protected $dispatchMode;
    protected $projectRoot;
    protected $documentRoot;

    public function __construct(Container $container, array $settings = [])
    {
        $this->container = $container;

        $this->host = $settings['host'] ?? '0.0.0.0';
        $this->port = $settings['port'] ?? 80;
        $this->workerNum = $settings['worker_num'] ?? 4; // 默认开启 4 个 worker 进程，一般设置成 CPU 核数的 1-4 倍
        $this->maxRequest = $settings['max_request'] ?? 3000;// 默认每个 worker 进程处理超过 300 个请求就重启
        $this->dispatchMode = $settings['dispatch_mode'] ?? 3;// 3-抢占模式，1- 默认轮循模式，收到会轮循分配给每一个 worker 进程,
        $this->projectRoot = $settings['project_root'] ?? '/var/www/html';
        $this->documentRoot = $settings['document_root'] ?? $this->projectRoot . '/assets';
    }

    public function run()
    {
        swoole_set_process_name("http-kernel master");

        $this->server = new \Swoole\Http\Server($this->host, $this->port);

        $this->server->set([
            'worker_num' => $this->workerNum,
            'max_request' => $this->maxRequest,
            'dispatch_mode' => $this->dispatchMode,
            'enable_static_handler' => true,
            'document_root' => $this->documentRoot,
        ]);

        $this->server->on('start', [$this, 'onStart']);
        $this->server->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->server->on('ManagerStart', [$this, 'onManagerStart']);
        $this->server->on('request', [$this, 'onRequest']);

        $this->server->start();
    }

    public function onRequest(Request $request, Response $response)
    {
        $requestData = $this->resolveRequestData($request);

        $environment = \Slim\Http\Environment::mock($_SERVER);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        if ($requestData) {
            $request = $request->withParsedBody($requestData);
        }

        $slimResponse = new \Slim\Http\Response();

        try {
            $slimResponse = $this->app->process($request, $slimResponse);
            $bodyContents = (string)$slimResponse->getBody();
            $contentType = $slimResponse->getHeaderLine("Content-Type");
            var_dump($contentType);
        } catch (\Exception $exception) {
            $bodyContents = "{'error':{$exception->getMessage()}}";
            $contentType = "";
        }

        $contentType = $contentType ?: "application/json;charset=utf-8";
        var_dump($contentType);
        $response->header("Content-Type", $contentType);
        $response->end($bodyContents);
        $this->unsetRequestData();
    }

    public function onWorkerStart(Server $server, int $workerId)
    {
        // onWorkerStart 之后的代码每个进程都需要在内存中保存一份（每处理 max_request 个请求后会重新加载一次）
        swoole_set_process_name("http-kernel worker {$workerId}");

        echo $workerId . " : " . __METHOD__ . PHP_EOL;

        $this->app = $this->container->raw(App::class);
    }

    public function onManagerStart(Server $server)
    {
        // onManagerStart 只在主进程启动的时候执行一次
        swoole_set_process_name("http-kernel manager");
    }

    public function onStart(Server $server)
    {
        HttpShare::setMasterPid($server->master_pid);
        HttpShare::setManagerPid($server->manager_pid);
    }

    /**
     * @param Request $request
     * @return array|mixed
     */
    private function resolveRequestData(Request $request)
    {
        $contentType = $request->header['content-type'] ?? '';
        if ('application/json' == $contentType) {
            $requestData = json_decode($request->rawContent(), true);
        } else {
            $get = $request->get ?? [];
            $post = $request->post ?? [];
            $requestData = array_merge($get, $post);
        }

        if (isset($request->header)) {
            foreach ($request->header as $key => $value) {
                $_SERVER["HTTP_" . strtoupper($key)] = $value;
            }
        }
        if (isset($request->server)) {
            foreach ($request->server as $key => $value) {
                $_SERVER[strtoupper($key)] = $value;
            }
        }

        return $requestData;
    }

    private function unsetRequestData()
    {
        unset($_SERVER);
    }
}