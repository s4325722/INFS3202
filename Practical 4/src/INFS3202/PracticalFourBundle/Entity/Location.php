<?php

namespace INFS3202\PracticalFourBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 *
 * @ORM\Table(name="location", indexes={@ORM\Index(name="fk_location_proprietor_idx", columns={"proprietor_id"})})
 * @ORM\Entity
 */
class Location
{
    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=100, nullable=true)
     */
    private $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \INFS3202\PracticalFourBundle\Entity\Proprietor
     *
     * @ORM\OneToOne(targetEntity="INFS3202\PracticalFourBundle\Entity\Proprietor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proprietor_id", referencedColumnName="id")
     * })
     */
    private $proprietor;



    /**
     * Set address
     *
     * @param string $address
     * @return Location
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
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
     * Set proprietor
     *
     * @param \INFS3202\PracticalFourBundle\Entity\Proprietor $proprietor
     * @return Location
     */
    public function setProprietor(\INFS3202\PracticalFourBundle\Entity\Proprietor $proprietor = null)
    {
        $this->proprietor = $proprietor;

        return $this;
    }

    /**
     * Get proprietor
     *
     * @return \INFS3202\PracticalFourBundle\Entity\Proprietor 
     */
    public function getProprietor()
    {
        return $this->proprietor;
    }
}
