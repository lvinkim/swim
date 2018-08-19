<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 12/07/2018
 * Time: 9:48 PM
 */

namespace Lvinkim\Swim\Service\Logger;

use Lvinkim\Swim\Service\Component\ShareableService;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class CustomLogger extends ShareableService
{
    private $loggerDirectory;
    private $app;

    private $binds = [];

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $settings = $container->get('settings');
        $this->loggerDirectory = $settings['logger']['directory'] ?? false;
        $this->app = $settings['app'] ?? "";

    }

    public function log($logName, array $content = [], $subDir = '')
    {
        if (false === $this->loggerDirectory) {
            return false;
        }

        $bindName = "log-{$logName}";
        if (!isset($this->binds[$bindName])) {

            $subDir = $subDir ? $subDir . '/' : '';
            $logPath = $this->loggerDirectory . "/{$subDir}{$logName}.log." . date('Y-m-d');
            try {
                $handler = new StreamHandler($logPath, Logger::INFO, true, 0777);
            } catch (\Exception $exception) {
                return false;
            }

            $handler->setFormatter(new JsonFormatter());

            $logger = new Logger($logName);
            $logger->pushHandler($handler);

            $processor = function ($record) {
                /** @var \DateTime $datetime */
                $datetime = $record['datetime'];
                $newRecord = [
                    'channel' => $record['channel'],
                    'level' => $record['level'],
                    'datetime' => $datetime->format('Y-m-d H:i:s'),
                    'context' => $record['context'],
                ];
                return $newRecord;
            };
            $logger->pushProcessor($processor);

            $this->binds[$bindName] = $logger;
        }

        /** @var $logger  Logger */
        $logger = $this->binds[$bindName];

        return $logger->info($logName, $content);
    }

    public function vision($type, $doc = [], $subDir = '')
    {
        if (false === $this->loggerDirectory) {
            return false;
        }

        $bindName = "vision-{$type}";
        if (!isset($this->binds[$bindName])) {

            $subDir = $subDir ? $subDir . '/' : '';
            $logPath = $this->loggerDirectory . "/vision/{$subDir}{$type}.log." . date('Y-m-d');;
            try {
                $handler = new StreamHandler($logPath, Logger::INFO, true, 0777);
            } catch (\Exception $exception) {
                return false;
            }
            $handler->setFormatter(new JsonFormatter);

            $logger = new Logger($type);
            $logger->pushHandler($handler);

            $processor = function ($record) {
                $newRecord = [
                    'host' => getenv('HOST_NICKNAME') ?: gethostname(),
                    'level' => $record['level'] ?? 200,
                ];
                $newRecord = array_merge($newRecord, $record['context'] ?? []);
                return $newRecord;
            };
            $logger->pushProcessor($processor);

            $this->binds[$bindName] = $logger;
        }

        /** @var $logger  Logger */
        $logger = $this->binds[$bindName];

        return $logger->info($type, $doc);
    }

    public function visionWatch($func, $item, $value)
    {
        $this->vision('app-watch', [
            'app' => $this->app,
            'func' => $func,
            'item' => $item,
            'value' => $value
        ], 'app-watch');
    }
}