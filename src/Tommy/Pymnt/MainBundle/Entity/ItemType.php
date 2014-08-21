<?php
/**
 * Created by IntelliJ IDEA.
 * User: tomfun
 * Date: 17.08.14
 * Time: 18:24
 */
namespace Tommy\Pymnt\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * ItemType
 *
 * @ORM\Table(name="item_type")
 * @ORM\Entity(repositoryClass="Tommy\Pymnt\MainBundle\Repo\ItemTypeRepository")
 */
class ItemType
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string $class
     *
     * @ORM\Column(name="class", type="string", length=20, nullable=false)
     */
    protected $class;

    /**
     * @var Item[] $items;
     * @ORM\OneToMany(targetEntity="Item", mappedBy="type")
     */
    protected $items;

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}