<?php
/**
 * Created by IntelliJ IDEA.
 * User: tomfun
 * Date: 17.08.14
 * Time: 18:53
 */
namespace Tommy\Pymnt\MainBundle\Repo;

use Doctrine\ORM\EntityRepository;
use Tommy\Pymnt\MainBundle\Entity\Item;

class ItemRepository extends EntityRepository
{
    /**
     * @param int $id
     * @return null|Item
     */
    public function getOneById($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

}