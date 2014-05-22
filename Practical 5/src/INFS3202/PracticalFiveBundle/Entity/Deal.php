<?php

namespace INFS3202\PracticalFiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Deal
 *
 * @ORM\Table(name="deal", indexes={@ORM\Index(name="deal_proprietor_idx", columns={"proprietor_id"}), @ORM\Index(name="deal_category_idx", columns={"category_id"})})
 * @ORM\Entity(repositoryClass="INFS3202\PracticalFiveBundle\Entity\DealRepository")
 */
class Deal
{
    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="banner", type="string", length=255, nullable=true)
     */
    private $banner;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=150, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp_start", type="datetime", nullable=true)
     */
    private $timestampStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp_end", type="datetime", nullable=true)
     */
    private $timestampEnd;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \INFS3202\PracticalFiveBundle\Entity\Proprietor
     *
     * @ORM\ManyToOne(targetEntity="INFS3202\PracticalFiveBundle\Entity\Proprietor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proprietor_id", referencedColumnName="id")
     * })
     */
    private $proprietor;

    /**
     * @var \INFS3202\PracticalFiveBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="INFS3202\PracticalFiveBundle\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;



    /**
     * Set price
     *
     * @param string $price
     * @return Deal
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set banner
     *
     * @param string $banner
     * @return Deal
     */
    public function setBanner($banner)
    {
        $this->banner = $banner;

        return $this;
    }

    /**
     * Get banner
     *
     * @return string 
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Deal
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Deal
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set timestampStart
     *
     * @param \DateTime $timestampStart
     * @return Deal
     */
    public function setTimestampStart($timestampStart)
    {
        $this->timestampStart = $timestampStart;

        return $this;
    }

    /**
     * Get timestampStart
     *
     * @return \DateTime 
     */
    public function getTimestampStart()
    {
        return $this->timestampStart;
    }

    /**
     * Set timestampEnd
     *
     * @param \DateTime $timestampEnd
     * @return Deal
     */
    public function setTimestampEnd($timestampEnd)
    {
        $this->timestampEnd = $timestampEnd;

        return $this;
    }

    /**
     * Get timestampEnd
     *
     * @return \DateTime 
     */
    public function getTimestampEnd()
    {
        return $this->timestampEnd;
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
     * @param \INFS3202\PracticalFiveBundle\Entity\Proprietor $proprietor
     * @return Deal
     */
    public function setProprietor(\INFS3202\PracticalFiveBundle\Entity\Proprietor $proprietor = null)
    {
        $this->proprietor = $proprietor;

        return $this;
    }

    /**
     * Get proprietor
     *
     * @return \INFS3202\PracticalFiveBundle\Entity\Proprietor 
     */
    public function getProprietor()
    {
        return $this->proprietor;
    }

    /**
     * Set category
     *
     * @param \INFS3202\PracticalFiveBundle\Entity\Category $category
     * @return Deal
     */
    public function setCategory(\INFS3202\PracticalFiveBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \INFS3202\PracticalFiveBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
