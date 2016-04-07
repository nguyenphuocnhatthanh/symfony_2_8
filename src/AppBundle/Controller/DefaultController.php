<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Exception\HandlerErrorsApi;
use AppBundle\Exception\TableNotMappingException;
use AppBundle\Service\ApiValidate;
use AppBundle\Service\TextMasterApi;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
    public function createProjectAction(Request $request)
    {
        $text_master = new TextMasterApi();
        $apiValidate = new ApiValidate();


     /*   $params = [
            'project' => [
                'name' => '',
                'activity_name' => '',
                'options' => [
                    'language_level' => 'enterprise',
                    'quality' => true,
                    'expertise' => '559564bc736f7617da000545'
                ],
                'vocabulary_type' => 'not_specified',
                'target_reader_groups' => 'not_specified',
                'grammatical_person' => 'not_specified',

                'language_from' => 'fr-fr',
                'language_to' => 'en-gb',
                'category' => 'C014',
                'project_briefing' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\n    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation\n    ullamco labo',
                'documents' => 'e',
            ]
        ];*/

//        die(dump($request->request->get('project')['options'], $request->request->all()));
        $data = $request->get('project');
        $project = new Project(
            $data['name'],
            $data['activity_name'],
            $data['options'],
            $data['language_from'],
            $data['language_to'],
            $data['category'],
            $data['project_briefing'],
            '2016-05-04 00:00:00',
            'test',
            true,
            '',
            $data['vocabulary_type'],
            $data['grammatical_person'],
            $data['target_reader_groups'],
            [],
            []
        );
//        die(dump($project));

        try {
            $errors = $this->get('validator')->validate($project);

            if (count($errors) > 0) {
                $errorsResponse =new HandlerErrorsApi($errors);
                die(dump( $errorsResponse->getErrors()));
            }
        } catch (TableNotMappingException $e) {
            return $e->getMessage();
        }
//die(dump());
        $params = $request->request->all();
//        $text_master->request();
        $temp = $text_master->createProject(json_encode($params));
        die;
    }
}
