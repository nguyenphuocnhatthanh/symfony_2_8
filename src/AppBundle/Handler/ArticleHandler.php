<?php


namespace AppBundle\Handler;


use AppBundle\Entity\Article;
use AppBundle\Form\Handler\FormHandler;
use AppBundle\Repository\ArticleRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleHandler implements HandlerInterface
{
    private $article;
    private $form;

    public function __construct(ArticleRepository $articleRepository, FormHandler $formHandler)
    {
        $this->article = $articleRepository;
        $this->form = $formHandler;
    }
    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        $data = $this->article->find($id);

        if (null == $data) {
            throw new NotFoundHttpException;
        }

        return $data;
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function all(array $options = [])
    {
        return $this->article->findBy($options['criteria'], $options['order'], $options['limit'], $options['offset']);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function post(array $params)
    {
        return $this->form->processForm(
            new Article(),
            $params,
            'POST'
        );
    }

    /**
     * @param $articleInterface
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function put($articleInterface, array $params)
    {
        if (! $articleInterface instanceof ArticleRepository) {
            throw new \Exception('Object not instance');
        }

        return $this->form->processForm(
            $articleInterface,
            $params,
            'PUT'
        );
    }

    /**
     * @param $articleInterface
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function patch($articleInterface, array $params)
    {
        if (! $articleInterface instanceof ArticleRepository) {
            throw new \Exception('Object not instance');
        }

        return $this->form->processForm(
            $articleInterface,
            $params,
            'PATCH'
        );
    }

    /**
     * @param $articleInterface
     * @return mixed
     * @throws \Exception
     */
    public function delete($articleInterface)
    {
        if (! $articleInterface instanceof ArticleRepository) {
            throw new \Exception('Object not instance');
        }

        return $this->form->delete($articleInterface);
    }
}