<?php
namespace AppBundle\Controller;

use AppBundle\Handler\ProjectHandler;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProjectController
 * @package AppBundle\Controller
 */
class ProjectController extends FOSRestController
{
    /**
     * @param Request $request
     * @return array|mixed
     */
    public function postProjectAction(Request $request)
    {
        try {
            $handler = new ProjectHandler($this->getValidator());

            return $handler->post($request->request->all());
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'message' => $e->getMessage(). '. File: '. $e->getFile(). '. Line:'.$e->getLine(),
                'fields' => []
            ];
        }
    }

    /**
     * Get Service Validator
     * @return object
     */
    private function getValidator()
    {
        return $this->get('validator');
    }
}
