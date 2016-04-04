<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\ArticleSearchType;
use AppBundle\Model\ArticleSearch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends FOSRestController
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

    /**
     * @QueryParam(name="limit", requirements="\d+",  description="our limit")
     * @QueryParam(name="offset", requirements="\d+", nullable=true, default="0", description="our offset")
     *
     * @param Request $request
     * @param ParamFetcherInterface $paramFetcherInterface
     * @return mixed
     */
    public function getArticlesAction(Request $request, ParamFetcherInterface $paramFetcherInterface)
    {
        $limit = $paramFetcherInterface->get('limit');
        $offset = $paramFetcherInterface->get('offset');

        return $this->getHandler()->all([
            'criteria' => [],
            'order' => [],
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function patchArticleAction(Request $request, $id)
    {
        try {
            $article = $this->getHandler()->get($id);
            $article = $this->getHandler()->patch($article, $request->request->all());

            return $article;
        } catch (NotFoundHttpException $e) {
            return $e->getMessage();
        }
    }

    private function getHandler()
    {
        return $this->get('app.app_bundle.handler.article_handle');
    }
}