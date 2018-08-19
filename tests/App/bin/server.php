<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 8:06 PM
 */

require dirname(__DIR__) . '/../../vendor/autoload.php';

$settings = require dirname(__DIR__) . '/config/settings.php';
$kernel = new \Lvinkim\Swim\Kernel($settings);

$console = new \Lvinkim\Swim\Console\Console($kernel);
$console->run();
