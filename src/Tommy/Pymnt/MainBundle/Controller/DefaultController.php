<?php

namespace Tommy\Pymnt\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TommyPymntMainBundle:Default:index.html.twig', array('name' => 'asdfasdf'));
    }
}
