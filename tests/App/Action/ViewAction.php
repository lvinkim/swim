<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 23/09/2018
 * Time: 12:08 AM
 */

namespace Tests\App\Action;


use Lvinkim\Swim\Action\Component\ActionInterface;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Tests\App\Service\PlatesService;

class ViewAction implements ActionInterface
{

    /** @var PlatesService */
    private $platesService;

    /**
     * ActionInterface constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->platesService = $container->get(PlatesService::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        return $this->platesService->render('view');
    }
}