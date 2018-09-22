<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/19
 * Time: 11:15 PM
 */

namespace Tests\App\Action;


use Lvinkim\Swim\Action\Component\ActionInterface;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Tests\App\Service\TestService;

class IndexAction implements ActionInterface
{
    /** @var TestService */
    private $testService;

    /**
     * ActionInterface constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->testService = $container->get(TestService::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        $this->testService->addKey();

        return $response->withJson([
            "data" => mt_rand(1000, 9999),
            "object" => $this->testService->getObject(),
        ]);
    }
}