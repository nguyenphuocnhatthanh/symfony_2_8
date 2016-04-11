<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DocumentController
 * @package AppBundle\Controller
 */
class DocumentController extends FOSRestController
{
    /**
     * @param Request $request
     * @return array
     */
    public function postDocumentAction(Request $request)
    {
        try {
            $handler = $this->getHandler();
            $params = array_merge(['project_id' => $request->get('project_id')], $request->request->all());

            return $handler->post($params);
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'message' => 'Server error.',
                'fields' => null
            ];
        }
    }

    /**
     * @return object
     */
    public function getHandler()
    {
        return $this->get('app.app_bundle.handler.document_handler');
    }
}
