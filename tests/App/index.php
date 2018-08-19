<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 2:52 PM
 */


require dirname(__DIR__) . '/../vendor/autoload.php';

$settings = require __DIR__ . '/config/settings.php';

$kernel = new \Lvinkim\Swim\Kernel($settings);

$app = new \Lvinkim\Swim\Console\Application($kernel);

$app->run();

