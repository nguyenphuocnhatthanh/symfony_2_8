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
            $handler = $this->getHandler();
            return $handler->post($request->request->all());
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'message' => $e->getMessage(). '. File: '. $e->getFile(). '. Line:'.$e->getLine(),
                'fields' => []
            ];
        }
    }

    public function getProjectFilterAction(Request $request)
    {
        try {
            $handler = $this->getHandler();
            return $handler->filter($request->query->all());
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'message' => $e->getMessage(). '. File: '. $e->getFile(). '. Line:'.$e->getLine(),
                'fields' => []
            ];
        }
    }
    /**
     * Get service handler
     * @return object
     */
    private function getHandler()
    {
        return $this->get('app.app_bundle.handler.project_handler');
    }
}
