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
use Tommy\Pymnt\MainBundle\Entity\User;

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

    /**
     * @param User $user
     * @return null|Item[]
     */
    public function getAllByUser($user)
    {
        return $this->findOneBy(['user' => $user->getId()]);
    }
}