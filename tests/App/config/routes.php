<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 10:26 PM
 */

/** @var \Slim\App $app */

$app->get('/', \Tests\App\Action\IndexAction::class);