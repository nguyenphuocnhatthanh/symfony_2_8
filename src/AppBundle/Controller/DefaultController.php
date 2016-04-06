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
        $params = json_encode([
            'project' => [
                'name' => 'translatetor test',
                'activity_name' => 'translation',
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
//                'deadline' => '2016-05-05',
            ]
        ]);

//        $json = '{"project":{"name":"factory_project","ctype":"proofreading","language_from":"fr-fr","language_to":"fr-fr","category":"C014","vocabulary_type":"not_specified","target_reader_groups":"not_specified","grammatical_person":"not_specified","project_briefing":"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\n    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation\n    ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in\n    voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\n    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.","options":{"language_level":"premium","expertise":"559564bc736f7617da000545"}}}';
//        die(dump($params));
//        $text_master->request();
        $temp = $text_master->createProject($params);
        die;
    }
}
