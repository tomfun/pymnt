<?php
/**
 * Created by IntelliJ IDEA.
 * User: tomfun
 * Date: 17.08.14
 * Time: 18:24
 */
namespace Tommy\Pymnt\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Tommy\Pymnt\MainBundle\Repo\UserRepository")
 */
class User implements UserInterface, \Serializable
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
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=140, unique=true, nullable=false)
     */
    protected $email;

    /**
     * Email confirmation code
     *
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=88, nullable=false)
     */
    protected $code;

    /**
     * Encrypted password
     *
     * @var string $hash
     *
     * @ORM\Column(name="hash", type="string", length=60, nullable=false)
     */
    protected $hash;

    /**
     * @var \Datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * Salt for password encryption
     *
     * @var string $hash
     *
     * @ORM\Column(name="salt", type="string", length=22, nullable=false)
     */
    protected $salt;

    /**
     * Whether email confirmed
     *
     * @var boolean $confirmed
     *
     * @ORM\Column(name="confirmed", type="boolean", nullable=false)
     */
    protected $confirmed;

    /**
     * @var boolean $informable
     *
     * @ORM\Column(name="informable", type="boolean", nullable=false)
     */
    protected $informable;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Phone", mappedBy="user")
     */
    protected $phones;

    /**
     * @ORM\OneToMany(targetEntity="Label", mappedBy="user")
     */
    protected $labels;

    // -------------------------------------

    /** @var  string $plainPassword */
    protected $plainPassword;

    /** @var  string $phone */
    protected $phone;

    /**
     * @return boolean
     */
    public function getInformable()
    {
        return $this->informable;
    }

    /**
     * @param boolean $informable
     */
    public function setInformable($informable)
    {
        $this->informable = $informable;
    }

    /**
     * @return mixed
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * @param mixed $phones
     */
    public function setPhones($phones)
    {
        $this->phones = $phones;
    }


    public function __construct()
    {
        $generator = new SecureRandom();
        $this->salt = substr(base64_encode($generator->nextBytes(22)), 0, 22);
        $this->code = urlencode(base64_encode($generator->nextBytes(22)));
        $this->createdAt = new \DateTime();
        $this->confirmed = false;
        $this->phones = new ArrayCollection();
        $this->informable = false;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return User
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->hash;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->hash,
            $this->createdAt,
            $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->hash,
            $this->createdAt,
            $this->salt,
            ) = unserialize($serialized);
    }

    /**
     * @param string|null $password
     * @param PasswordEncoderInterface $encoder
     * @return $this
     */
    public function setPlainPassword($password, PasswordEncoderInterface $encoder = null)
    {
        if ($encoder === null) {
            $this->plainPassword = $password;
        } else {
            if ($password === null) {
                $password = $encoder->encodePassword($this->plainPassword, $this->getSalt());
            } else {
                $password = $encoder->encodePassword($password, $this->getSalt());
            }
            $this->setHash($password);
        }
        return $this;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return User
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set confirmed
     *
     * @param boolean $confirmed
     * @return User
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get confirmed
     *
     * @return boolean
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
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
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }
}
