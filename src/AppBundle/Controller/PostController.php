<?php

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostController extends Controller
{
    /**
     * @Route("/posts/{id}", name="post_show")
     */
    public function showAction($id)
    {
        // get a Post object - e.g. query for it
        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($id);


        // check for "view" access: calls all voters
        $this->denyAccessUnlessGranted('view', $post);
        die(dump('hehe'));
        // ...
    }

    /**
     * @Route("/posts/{id}/edit", name="post_edit")
     */
    public function editAction($id)
    {
        // get a Post object - e.g. query for it
        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($id);

        // check for "edit" access: calls all voters
        $this->denyAccessUnlessGranted('edit', $post);

        // ...
    }
}