<?php

namespace Tommy\Pymnt\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tommy\Pymnt\MainBundle\Entity\Item;
use Tommy\Pymnt\MainBundle\Entity\ItemType;
use Tommy\Pymnt\MainBundle\Entity\Part;
use Tommy\Pymnt\MainBundle\Entity\Spending;
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

    protected function getAddForm(){
        $item = new Item();
        $form = $this->createForm('item', $item, ['action' => $this->generateUrl('tommy_pymnt_main_cabinet_addform_process')]);
        return $form;
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

    public function itemListAction()
    {
        $items = $this->get('item_repository')->findAll();
        return $this->render('TommyPymntMainBundle:Cabinet:itemList.html.twig', array('items' => $items));
    }

    public function addItemAction(){
        $form = $this->getAddForm();
        return $this->render('TommyPymntMainBundle:Cabinet:addItem.html.twig', array('form' => $form->createView()));
    }

    public function addItemProcessAction(Request $request){
        $form = $this->getAddForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Item $data */
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
        }
        return $this->render('TommyPymntMainBundle:Cabinet:addItem.html.twig', array('form' => $form->createView()));
    }

    public function itemCloseAction($id){
        $item = $this->get('item_repository')->find($id);
        /** @var Item $item */
        $item->setClosedAt(new \DateTime());
        if($item->getType()->getClass()==ItemType::purchase){
            $spending = new Spending();
            $spending->setItem($item);
            $price = $item->getPrice();
            foreach($item->getParts() as $part){
                /** @var Part $part */
                $part->getPayments();
                //todo: calc price based on payments
            }
            $spending->setPrice($price);
        }
        return $this->redirect($this->generateUrl('tommy_pymnt_main_cabinet_items'));
    }

}
