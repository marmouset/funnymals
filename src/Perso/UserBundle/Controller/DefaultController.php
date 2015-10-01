<?php

namespace Perso\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PersoUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
