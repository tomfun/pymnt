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
 * Part
 *
 * @ORM\Table(name="part")
 * @ORM\Entity(repositoryClass="Tommy\Pymnt\MainBundle\Repo\PartRepository")
 */
class Part
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Item $item
     *
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="parts")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id", nullable=false)
     */
    protected $item;

    /**
     * @var Label $label
     *
     * @ORM\ManyToOne(targetEntity="Label", inversedBy="parts")
     * @ORM\JoinColumn(name="label_id", referencedColumnName="id", nullable=false)
     */
    protected $label;

    /**
     * @var float $used
     *
     * @ORM\Column(name="used", type="decimal", nullable=false)
     */
    protected $used;

    /**
     * @var boolean $isFixed
     *
     * @ORM\Column(name="is_fixed", type="boolean", nullable=false)
     */
    protected $isFixed;

    /**
     * To shto on doljen
     *
     * @var float $price
     *
     * @ORM\Column(name="price", type="decimal", nullable=true)
     */
    protected $price;

    /**
     * @return float
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * @param float $used
     */
    public function setUsed($used)
    {
        $this->used = $used;
    }

    /**
     * @return boolean
     */
    public function getIsFixed()
    {
        return $this->isFixed;
    }

    /**
     * @param boolean $isFixed
     */
    public function setIsFixed($isFixed)
    {
        $this->isFixed = $isFixed;
    }

    /**
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Item $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }

    /**
     * @return Label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param Label $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
