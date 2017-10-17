<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Receipt
 *
 * @ORM\Table(name="receipt")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReceiptRepository")
 */
class Receipt
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var Housing
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Housing", inversedBy="receipts")
     */
    private $housing;

    /**
     * @var Tenant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tenant", inversedBy="receipts")
     */
    private $tenant;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Receipt
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set housing
     *
     * @param Housing $housing
     *
     * @return Receipt
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
     * Set tenant
     *
     * @param Tenant $tenant
     *
     * @return Receipt
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
}
