<?php

namespace Tommy\Pymnt\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tommy\Pymnt\MainBundle\Entity\Item;
use Tommy\Pymnt\MainBundle\Entity\User;

class CabinetController extends Controller
{
    /**
     * @return \Symfony\Component\Security\Core\SecurityContext
     */
    protected function getSecurity()
    {
        return $this->get('security.context');
    }

    /**
     * @throws \Exception
     * @return User
     */
    public function getUser()
    {
        $user =parent::getUser();
        if(!($user instanceof User)){
            throw new \Exception('This is not a User!');
        }
        return $user;
    }

    public function indexAction()
    {
        $usr = $this->getUser();
        $name = $usr->getEmail();
        return $this->render('TommyPymntMainBundle:Cabinet:index.html.twig', array('name' => $name));
    }

    public function addItemAction(){
        $item = new Item();
        $form = $this->createForm('item', $item);
        return $this->render('TommyPymntMainBundle:Cabinet:addItem.html.twig', array('form' => $form->createView()));
    }
}
