<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 4:18 PM
 */

return (function () {

    "prod" === getenv("ENV") ? error_reporting(0) : null;

    return [
        "env" => getenv("ENV"),
        "debug" => getenv("DEBUG"),
        "displayErrorDetails" => "dev" === getenv("ENV") ? true : false, // set to false in production
        "addContentLengthHeader" => false, // Allow the web server to send the content-length header
        "projectDir" => dirname(__DIR__),
        'logger' => [
            'directory' => dirname(__DIR__) . '/var/logs',
        ],
        "bundles" => __DIR__ . "/bundles.php",
        "dependencies" => __DIR__ . "/dependencies.php",
        "middleware" => __DIR__ . "/middleware.php",
        "routes" => __DIR__ . "/routes.php",
    ];

})();
