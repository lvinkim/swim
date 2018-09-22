<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 23/09/2018
 * Time: 5:43 AM
 */

namespace Tests\App\Service;


use Lvinkim\Swim\Service\Component\ShareableService;
use Psr\Container\ContainerInterface;

class TestService extends ShareableService
{

    private $object;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->object = new \stdClass();
    }

    public function addKey()
    {
        $key = posix_getpid() . "-" . time();
        $this->object->{$key} = 1;
    }

    /**
     * @return \stdClass
     */
    public function getObject(): \stdClass
    {
        return $this->object;
    }

}