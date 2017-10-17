<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Housing
 *
 * @ORM\Table(name="housing")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HousingRepository")
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 */
class Housing
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
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="rent", type="float")
     * @Gedmo\Versioned
     */
    private $rent;

    /**
     * @var float
     *
     * @ORM\Column(name="costs", type="float")
     * @Gedmo\Versioned
     */
    private $costs;

    /**
     * @var Building
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Building", inversedBy="housings")
     * @Gedmo\Versioned
     */
    private $building;

    /**
     * @var Tenant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Tenant", inversedBy="housing")
     * @Gedmo\Versioned
     */
    private $tenant;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Receipt", mappedBy="housing")
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
     * Set name
     *
     * @param string $name
     *
     * @return Housing
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set rent
     *
     * @param float $rent
     *
     * @return Housing
     */
    public function setRent($rent)
    {
        $this->rent = $rent;

        return $this;
    }

    /**
     * Get rent
     *
     * @return float
     */
    public function getRent()
    {
        return $this->rent;
    }

    /**
     * Set costs
     *
     * @param float $costs
     *
     * @return Housing
     */
    public function setCosts($costs)
    {
        $this->costs = $costs;

        return $this;
    }

    /**
     * Get costs
     *
     * @return float
     */
    public function getCosts()
    {
        return $this->costs;
    }

    public function getTotal()
    {
        return $this->rent + $this->costs;
    }

    /**
     * Set building
     *
     * @param Building $building
     *
     * @return Housing
     */
    public function setBuilding(Building $building = null)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * Get building
     *
     * @return Building
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Set tenant
     *
     * @param Tenant $tenant
     *
     * @return Housing
     */
    public function setTenant(Tenant $tenant = null)
    {
        $this->tenant = $tenant;

        return $this;
    }

    /**
     * Get tenant
     *
     * @return Tenant
     */
    public function getTenant()
    {
        return $this->tenant;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Housing
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
     * @return Housing
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
