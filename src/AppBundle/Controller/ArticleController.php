<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\ArticleSearchType;
use AppBundle\Model\ArticleSearch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{
    /**
     * @Route("/article")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $articleSearch = new ArticleSearch();

        $articleSearchForm = $this->createForm(ArticleSearchType::class, $articleSearch, [
            'method' => 'POST'
        ]);
        $articleSearchForm->handleRequest($request);
        if ($articleSearchForm->isSubmitted()) {
            $articleSearch = $articleSearchForm->getData();
            $elasticaManager = $this->get('fos_elastica.manager');
            $results = $elasticaManager->getRepository('AppBundle:Article')->search($articleSearch);
//            die(dump($results));
        }


        return $this->render('article/list.html.twig',array(
            'results' => !empty($results) ? $results : [],
            'form' => $articleSearchForm->createView(),
        ));
    }
}