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
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="Tommy\Pymnt\MainBundle\Repo\ItemRepository")
 */
class Item
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
     * @var ItemType $type
     *
     * @ORM\ManyToOne(targetEntity="ItemType", inversedBy="items")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    protected $type;

    /**
     * @var string $caption
     *
     * @ORM\Column(name="caption", type="string", length=80, nullable=false)
     */
    protected $caption;

    /**
     * @var float $price
     *
     * @ORM\Column(name="price", type="decimal", nullable=true)
     */
    protected $price;

    /**
     * @var string $currency
     *
     * @ORM\Column(name="currency", type="string", length=20, nullable=true)
     */
    protected $currency;

    /**
     * @var \Datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \Datetime $closedAt
     *
     * @ORM\Column(name="closed_at", type="datetime", nullable=true)
     */
    protected $closedAt;

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param string $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ItemType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ItemType $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

}
