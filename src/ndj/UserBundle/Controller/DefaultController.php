<?php

namespace ndj\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ndjUserBundle:Default:index.html.twig');
    }
}
