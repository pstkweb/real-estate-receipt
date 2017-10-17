<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Tenant
 *
 * @ORM\Table(name="tenant")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TenantRepository")
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 */
class Tenant
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=100)
     * @Gedmo\Versioned
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=100)
     * @Gedmo\Versioned
     */
    private $lastName;

    /**
     * @var Housing
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Housing", mappedBy="tenant")
     * @Gedmo\Versioned
     */
    private $housing;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Receipt", mappedBy="tenant")
     */
    private $receipts;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    private $deletedAt;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->receipts = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Tenant
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Tenant
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set housing
     *
     * @param Housing $housing
     *
     * @return Tenant
     */
    public function setHousing(Housing $housing = null)
    {
        $this->housing = $housing;

        return $this;
    }

    /**
     * Get housing
     *
     * @return Housing
     */
    public function getHousing()
    {
        return $this->housing;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Tenant
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Add receipt
     *
     * @param Receipt $receipt
     *
     * @return Tenant
     */
    public function addReceipt(Receipt $receipt)
    {
        $this->receipts[] = $receipt;

        return $this;
    }

    /**
     * Remove receipt
     *
     * @param Receipt $receipt
     */
    public function removeReceipt(Receipt $receipt)
    {
        $this->receipts->removeElement($receipt);
    }

    /**
     * Get receipts
     *
     * @return Collection
     */
    public function getReceipts()
    {
        return $this->receipts;
    }
}
