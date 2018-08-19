<?php
/**
 * Created by PhpStorm.
 * User: hong
 * Date: 5/10/17
 * Time: 12:02 PM
 */

namespace Lvinkim\Swim\Action\Component;


use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Interface ActionInterface
 *
 * @package XYH\Tokyo\Action
 */
interface ActionInterface
{
    /**
     * ActionInterface constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container);

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, array $args);
}
