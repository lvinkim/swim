<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 10:26 PM
 */

/** @var \Slim\App $app */

if (getenv('ENV') == 'prod') {
    $app->add(new \Tuupola\Middleware\HttpBasicAuthentication([
        "secure" => false,
        "users" => [
            'admin' => getenv('API_PASSWORD') ?: "admin",
        ],
        "error" => function (\Slim\Http\Response $response, $arguments) {
            return $response->withStatus(401)
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->write(json_encode([
                    'success' => false,
                    'message' => '401 Unauthorized',
                    'data' => []
                ]));
        }
    ]));
}