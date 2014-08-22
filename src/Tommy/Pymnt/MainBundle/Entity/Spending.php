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
 * Spending
 *
 * @ORM\Table(name="spending")
 * @ORM\Entity(repositoryClass="Tommy\Pymnt\MainBundle\Repo\PartRepository")
 */
class Spending
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
     * @ORM\OneToOne(targetEntity="Item", inversedBy="spending")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id", nullable=false)
     */
    protected $item;

    /**
     * To shto potracheno
     *
     * @var float $price
     *
     * @ORM\Column(name="price", type="decimal", nullable=false)
     */
    protected $price;

    /**
     * @var \Datetime $accountedAt
     *
     * @ORM\Column(name="accounted_at", type="datetime", nullable=true)
     */
    protected $accountedAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Datetime
     */
    public function getAccountedAt()
    {
        return $this->accountedAt;
    }

    /**
     * @param \Datetime $accountedAt
     */
    public function setAccountedAt($accountedAt)
    {
        $this->accountedAt = $accountedAt;
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

}
