<?php

namespace App\Entity;

/**
 * Simple DTO
 * Class User
 * @package App\Entity
 */
class User implements \Serializable
{

    const ROOT = 1;

    const USER = 0 ;

    /**
     * @var string
     */
    protected $emailAddress;

    /**
     * @var string
     */
    protected $fullName;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string|\DateTime
     */
    protected $createdAt;

    /**
     * @var string|\DateTime
     */
    protected $updatedAt;

    /**
     * @var int
     */
    protected $type ;

    public function __construct()
    {
        $this->createdAt = \App\formatDateTime(new \DateTime());
        $this->updatedAt = \App\formatDateTime(new \DateTime());
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     * @return User
     */
    public function setEmailAddress(string $emailAddress): User
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @return User
     */
    public function setFullName(string $fullName): User
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password = null): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return \DateTime|string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|string $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = ($createdAt instanceof \DateTime) ? \App\formatDateTime($createdAt) : $createdAt;

        return $this;
    }

    /**
     * @return \DateTime|string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|string $updatedAt
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = ($updatedAt instanceof \DateTime) ? \App\formatDateTime($updatedAt) : $updatedAt;

        return $this;
    }


    public function serialize()
    {
        return serialize($this) ;
    }

    public function unserialize($string)
    {
        /**
         * @var User $unserialized
         */
        $unserialized = unserialize($string);

        $unserialized->setPassword(null);

        return $unserialized ;
    }

    /**
     * @param int $type
     * @return $this
     */
    public function setType(int $type)
    {
        $this->type = $type;

        return $this;
    }


}
