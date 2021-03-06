<?php

namespace Tommy\Pymnt\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tommy\Pymnt\MainBundle\Entity\Item;
use Tommy\Pymnt\MainBundle\Entity\ItemType;
use Tommy\Pymnt\MainBundle\Entity\Part;
use Tommy\Pymnt\MainBundle\Entity\Spending;
use Tommy\Pymnt\MainBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;

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
     * @return \Tommy\Pymnt\MainBundle\Repo\ItemRepository
     */
    protected function getItemRepo()
    {
        return $this->get('item_repository');
    }

    protected function getAddForm()
    {
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
        $user = parent::getUser();
        if (!($user instanceof User)) {
            throw new \Exception('This is not a User!');
        }
        return $user;
    }


    /**
     * @Rest\Get("/cabinet", name="tommy_pymnt_main_cabinet")
     * @Rest\View()
     */
    public function indexAction()
    {
        $usr = $this->getUser();
        $name = $usr->getEmail();
        return ['name' => $name];
    }


    /**
     * @Rest\Get("/cabinet/items", name="tommy_pymnt_main_cabinet_items")
     * @Rest\View()
     */
    public function itemListAction()
    {
        $items = $this->get('item_repository')->getAllByUser($this->getUser());
        return ['items' => $items];
    }


    /**
     * @Rest\Get("/cabinet/item/add", name="tommy_pymnt_main_cabinet_addform")
     * @Rest\View()
     */
    public function addItemAction()
    {
        $form = $this->getAddForm();
        return  ['form' => $form->createView()];
    }


    /**
     * @Rest\Get("/cabinet/item/add/process", name="tommy_pymnt_main_cabinet_addform_process")
     * @Rest\View()
     */
    public function addItemProcessAction(Request $request)
    {
        $form = $this->getAddForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Item $data */
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            return $this->redirect($this->generateUrl('tommy_pymnt_main_cabinet_part_index', ['itemId' => $data->getId(),]));
        } else {
            return ['form' => $form->createView()];
        }
    }

    /**
     * @Rest\Get("/cabinet/part/{itemId}", name="tommy_pymnt_main_cabinet_part_index")
     * @Rest\View()
     */
    public function manageParts($itemId)
    {
        $item = $this->getItemRepo()->getOneById($itemId);
        $parts = $item->getParts();
        if ($item->getType() == ItemType::purchase) {
            if (count($parts) < 1) {
                $parts[] = new Part();
            }
        } else {
            if (count($parts) < 1) {
                $parts[] = new Part();
                $parts[] = new Part();
            } elseif (count($parts) == 1) {
                $parts[] = new Part();
            }
        }
        $metaParts = [];
        foreach ($parts as $part) {
            $part->setItem($item);
            $form = $this->createForm('part', $part, ['action' => $this->generateUrl('tommy_pymnt_main_cabinet_part_process')]);
            $metaParts[] = $form->createView();
        }
        return ['metaParts' => $metaParts];
        //todo: ajax, add part, remove part
    }

    /**
     * @Rest\Get("/cabinet/part/process", name="tommy_pymnt_main_cabinet_part_process")
     * @Rest\View()
     */
    public function partProcess(Request $request)
    {
        //todo
    }

    /**
     * @Rest\Get("/cabinet/item/close/{id}", name="tommy_pymnt_main_cabinet_item_close")
     * @Rest\View()
     */
    public function itemCloseAction($id)
    {
        $item = $this->getItemRepo()->find($id);
        /** @var Item $item */
        $item->setClosedAt(new \DateTime());
        if ($item->getType()->getClass() == ItemType::purchase) {
            $spending = new Spending();
            $spending->setItem($item);
            $price = $item->getPrice();
            foreach ($item->getParts() as $part) {
                /** @var Part $part */
                $part->getPayments();
                //todo: calc price based on payments
            }
            $spending->setPrice($price);
        }
        return $this->redirect($this->generateUrl('tommy_pymnt_main_cabinet_items'));
    }

}
