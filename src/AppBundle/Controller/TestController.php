<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class TestController
 * @package AppBundle\Controller
 */
class TestController extends FOSRestController
{
    public function testAction()
    {
        return ['test' => 'test'];
    }
}
