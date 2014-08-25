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
 * Label
 *
 * @ORM\Table(name="label")
 * @ORM\Entity(repositoryClass="Tommy\Pymnt\MainBundle\Repo\LabelRepository")
 */
class Label
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
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", length=40, nullable=false)
     */
    protected $phone;

    /**
     * @var string $caption
     *
     * @ORM\Column(name="caption", type="string", length=80, nullable=false)
     */
    protected $caption;

    /**
     * Each user has own contact list
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="labels")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Part", mappedBy="label")
     */
    protected $parts;

    /**
     * @return mixed
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
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
