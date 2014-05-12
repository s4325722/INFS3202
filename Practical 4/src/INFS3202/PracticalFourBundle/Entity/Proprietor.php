<?php

namespace INFS3202\PracticalFourBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proprietor
 *
 * @ORM\Table(name="proprietor", uniqueConstraints={@ORM\UniqueConstraint(name="name_UNIQUE", columns={"name"})})
 * @ORM\Entity
 */
class Proprietor
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=8, nullable=true)
     */
    private $phone;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \INFS3202\PracticalFourBundle\Entity\Location
     *
     * @ORM\OneToOne(targetEntity="INFS3202\PracticalFourBundle\Entity\Location")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="proprietor_id")
     * })
     */
    private $location;

    /**
     * Set name
     *
     * @param string $name
     * @return Proprietor
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
     * Set phone
     *
     * @param string $phone
     * @return Proprietor
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
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
     * Set locationn
     *
     * @param \INFS3202\PracticalFourBundle\Entity\Location $location
     * @return Deal
     */
    public function setLocation(\INFS3202\PracticalFourBundle\Entity\Location $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \INFS3202\PracticalFourBundle\Entity\Location
     */
    public function getLocation()
    {
        return $this->location;
    }
}
