<?php
// src/Controller/BlogController.php

namespace App\Controller;

use App\Entity\Blog;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Blog controller.
 */
class BlogController extends Controller
{
    /**
     * Show a blog entry
     * @Route("/{id}", name="blog", methods={"GET"})
     * @param Blog $blog
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Blog $blog)
    {
        $em = $this->getDoctrine()->getManager();

        $blog = $em->getRepository('App:Blog')->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        $comments = $em->getRepository('App:Comment')
            ->getCommentsForBlog($blog->getId());

        return $this->render('blog/show.html.twig', array(
            'blog'      => $blog,
            'comments'  => $comments
        ));


    }

}