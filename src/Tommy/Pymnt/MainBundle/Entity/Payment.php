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
 * Payment
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="Tommy\Pymnt\MainBundle\Repo\PaymentRepository")
 */
class Payment
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
     * If null then payment to store
     * @var Part $partTo
     *
     * @ORM\ManyToOne(targetEntity="Part", inversedBy="payments")
     * @ORM\JoinColumn(name="part_to_id", referencedColumnName="id", nullable=true)
     */
    protected $partTo;

    /**
     * @var Part $partFrom
     *
     * @ORM\ManyToOne(targetEntity="Part", inversedBy="paymentsFrom")
     * @ORM\JoinColumn(name="part_from_id", referencedColumnName="id", nullable=false)
     */
    protected $partFrom;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    protected $description;

    /**
     * @var float $amount
     *
     * @ORM\Column(name="amount", type="decimal", nullable=false)
     */
    protected $amount;

    /**
     * @var \Datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return Part
     */
    public function getPartFrom()
    {
        return $this->partFrom;
    }

    /**
     * @param Part $partFrom
     */
    public function setPartFrom($partFrom)
    {
        $this->partFrom = $partFrom;
    }

    /**
     * @return Part
     */
    public function getPartTo()
    {
        return $this->partTo;
    }

    /**
     * @param Part $partTo
     */
    public function setPartTo($partTo)
    {
        $this->partTo = $partTo;
    }

}
