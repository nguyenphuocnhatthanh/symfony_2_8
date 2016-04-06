<?php

namespace AppBundle\Controller;

use AppBundle\Service\TextMasterApi;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
     * @Route("/admin")
     */
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }

    /**
     * @Route("/textmaster")
     */
    public function testApiAction()
    {
        $text_master = new TextMasterApi();
        $temp = $text_master->request();
        die;
    }

    /**
     * @Route("/create_project")
     */
    public function createProjectAction()
    {
        $text_master = new TextMasterApi();
        $param_prj = [
            'project' => [
                'name' => 'name project',
                'activity_name' => 'test test test',
                'level_name' => 'test',
                'options' => [
                    'language_level' => 'premium',
                    'expertise' => '54e1d07d868aa244ac31ea01'
                ],
                'language_from' => 'fr-fr',
                'language_to' => 'fr-fr',
                'category' => 'C014',
                'project_briefing' => 'test',
                'deadline' => '2016-05-05',
            ]
        ];
        $params = [
            'data' => json_encode($param_prj)
        ];

//        die(dump($params));
//        $text_master->request();
        $temp = $text_master->createProject($params);
        die;
    }
}
