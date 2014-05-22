<?php

namespace INFS3202\PracticalFiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment", indexes={@ORM\Index(name="fk_comment_deal_idx", columns={"deal_id"})})
 * @ORM\Entity
 */
class Comment
{
    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255, nullable=true)
     */
    private $text;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \INFS3202\PracticalFiveBundle\Entity\Deal
     *
     * @ORM\ManyToOne(targetEntity="INFS3202\PracticalFiveBundle\Entity\Deal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="deal_id", referencedColumnName="id")
     * })
     */
    private $deal;



    /**
     * Set text
     *
     * @param string $text
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
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
     * Set deal
     *
     * @param \INFS3202\PracticalFiveBundle\Entity\Deal $deal
     * @return Comment
     */
    public function setDeal(\INFS3202\PracticalFiveBundle\Entity\Deal $deal = null)
    {
        $this->deal = $deal;

        return $this;
    }

    /**
     * Get deal
     *
     * @return \INFS3202\PracticalFiveBundle\Entity\Deal 
     */
    public function getDeal()
    {
        return $this->deal;
    }
}
