<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 2:52 PM
 */


use Symfony\Component\Console\Input\ArrayInput;

require dirname(__DIR__) . '/../../vendor/autoload.php';

$settings = require __DIR__ . '/../config/settings.php';

$kernel = new \Lvinkim\Swim\Kernel($settings);

$app = new \Lvinkim\Swim\Console\Application($kernel);

$commandInput = new ArrayInput(['command' => "slim:run"]);

$app->run($commandInput);

