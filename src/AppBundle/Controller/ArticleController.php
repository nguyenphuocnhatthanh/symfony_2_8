<?php

namespace AppBundle\Controller;

use AppBundle\Form\FormInvalidException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\ArticleSearchType;
use AppBundle\Model\ArticleSearch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of Artists",
     *  section="Artists",
     *  requirements={
     *      {"name"="limit", "dataType"="integer", "requirement"="\d+", "description"="the max number of records to return"}
     *  },
     *  parameters={
     *      {"name"="limit", "dataType"="integer", "required"=true, "description"="the max number of records to return"},
     *      {"name"="offset", "dataType"="integer", "required"=false, "description"="the record number to start results at"}
     *  }
     * )
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
    public function putArticleAction(Request $request, $id)
    {
        try {
            die(dump($request->request->all()));
            $article = $this->getHandler()->get($id);
            $article = $this->getHandler()->put($article, $request->request->all());

            return $article;
        } catch (NotFoundHttpException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Replaces an existing Article",
     *  input = "AppBundle\Form\Type\ArticleType",
     *  output = "AppBundle\Form\Type\ArticleType",
     *  section="Artists",
     *  statusCodes={
     *         201="Returned when a new Article has been successfully created",
     *         400="Returned when the posted data is invalid"
     *     }
     * )
     * @param Request $request
     * @return array|\FOS\RestBundle\View\View
     */
    public function postArticleAction(Request $request)
    {
        try {
            $response = ['status' => Response::HTTP_CREATED,
                'data' => $this->getHandler()->post(
                $request->request->all()
            )];

            return $response;
        } catch (FormInvalidException $e) {
            return $this->view($e->getForm(), 400);
        }
    }

    private function getHandler()
    {
        return $this->get('app.app_bundle.handler.article_handle');
    }
}