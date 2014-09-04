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
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
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
     * @var Part[]
     * @ORM\OneToMany(targetEntity="Part", mappedBy="item")
     */
    protected $parts;

    /**
     * @var Spending[]
     * @ORM\OneToMany(targetEntity="Spending", mappedBy="item")
     */
    protected $spending;

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
     * @return \Datetime
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }

    /**
     * @param \Datetime $closedAt
     */
    public function setClosedAt($closedAt)
    {
        $this->closedAt = $closedAt;
    }

    /**
     * @return \Datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \Datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return Part[]
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * @param mixed $parts
     */
    public function setParts($parts)
    {
        $this->parts = $parts;
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

    /**
     * @return Spending[]
     */
    public function getSpending()
    {
        return $this->spending;
    }

    /**
     * @param Spending[] $spending
     */
    public function setSpending($spending)
    {
        $this->spending = $spending;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getIsClosed()
    {
        return $this->getClosedAt() !== null;
    }

    public function setIsClosed()
    {
        $this->setClosedAt(new \DateTime());
        return $this;
    }
}
