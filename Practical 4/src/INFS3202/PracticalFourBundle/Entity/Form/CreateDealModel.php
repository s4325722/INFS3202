<?php
/**
 * Created by PhpStorm.
 * User: Blake
 * Date: 18/05/2014
 * Time: 7:29 PM
 */

namespace INFS3202\PracticalFourBundle\Entity\Form;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;


class CreateDealModel extends ContainerAware {

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="100")
     */
    protected $categoryName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="100")
     */
    protected $categoryDescription;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="100")
     */
    protected $proprietorName;

    /**
     * @var string
     * @Assert\Length(min="10", max="10")
     */
    protected $proprietorPhoneNumber;

    /**
     * @var string
     */
    protected $proprietorAddress;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    protected $dealTitle;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    protected $dealDescription;

    /**
     * @var float
     * @Assert\Type(type="float")
     * @Assert\Range(min="0", max=1000)
     * @Assert\NotBlank()
     */
    protected $dealPrice;

    /**
     * @var \DateTime
     * @Assert\Date()
     */
    protected $dealStartDate;

    /**
     * @var \DateTime
     * @Assert\Date()
     */
    protected $dealEndDate;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    protected $dealBanner;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    protected $reviews;

    /**
     * @param string $categoryDescription
     */
    public function setCategoryDescription($categoryDescription)
    {
        $this->categoryDescription = $categoryDescription;
    }

    /**
     * @return string
     */
    public function getCategoryDescription()
    {
        return $this->categoryDescription;
    }

    /**
     * @param string $categoryName
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;
    }

    /**
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * @param string $dealBanner
     */
    public function setDealBanner($dealBanner)
    {
        $this->dealBanner = $dealBanner;
    }

    /**
     * @return string
     */
    public function getDealBanner()
    {
        return $this->dealBanner;
    }

    /**
     * @param string $dealDescription
     */
    public function setDealDescription($dealDescription)
    {
        $this->dealDescription = $dealDescription;
    }

    /**
     * @return string
     */
    public function getDealDescription()
    {
        return $this->dealDescription;
    }

    /**
     * @param \DateTime $dealEndDate
     */
    public function setDealEndDate($dealEndDate)
    {
        $this->dealEndDate = $dealEndDate;
    }

    /**
     * @return \DateTime
     */
    public function getDealEndDate()
    {
        return $this->dealEndDate;
    }

    /**
     * @param float $dealPrice
     */
    public function setDealPrice($dealPrice)
    {
        $this->dealPrice = $dealPrice;
    }

    /**
     * @return float
     */
    public function getDealPrice()
    {
        return $this->dealPrice;
    }

    /**
     * @param \DateTime $dealStartDate
     */
    public function setDealStartDate($dealStartDate)
    {
        $this->dealStartDate = $dealStartDate;
    }

    /**
     * @return \DateTime
     */
    public function getDealStartDate()
    {
        return $this->dealStartDate;
    }

    /**
     * @param string $dealTitle
     */
    public function setDealTitle($dealTitle)
    {
        $this->dealTitle = $dealTitle;
    }

    /**
     * @return string
     */
    public function getDealTitle()
    {
        return $this->dealTitle;
    }

    /**
     * @param string $proprietorAddress
     */
    public function setProprietorAddress($proprietorAddress)
    {
        $this->proprietorAddress = $proprietorAddress;
    }

    /**
     * @return string
     */
    public function getProprietorAddress()
    {
        return $this->proprietorAddress;
    }

    /**
     * @param string $proprietorName
     */
    public function setProprietorName($proprietorName)
    {
        $this->proprietorName = $proprietorName;
    }

    /**
     * @return string
     */
    public function getProprietorName()
    {
        return $this->proprietorName;
    }

    /**
     * @param string $proprietorPhoneNumber
     */
    public function setProprietorPhoneNumber($proprietorPhoneNumber)
    {
        $this->proprietorPhoneNumber = $proprietorPhoneNumber;
    }

    /**
     * @return string
     */
    public function getProprietorPhoneNumber()
    {
        return $this->proprietorPhoneNumber;
    }

    /**
     * @param string $reviews
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
    }

    /**
     * @return string
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        $em = $this->container->get('doctrine')->getManager();

        if($em->getRepository('INFS3202PracticalFourBundle:Deal')->findOneBy(['title' => $this->getDealTitle()]) != null){
            $context->addViolationAt('dealTitle', 'A deal with this title already exists.');
        }

        if(trim($this->getReviews()) == ''){
            $context->addViolationAt('reviews', 'This value must contain at least two valid reviews');
        }else{
            $reviews = explode("\n", $this->getReviews());

            for($i = 0; $i < count($reviews); $i++){
                $line = $i+1;
                if (!preg_match('/\A.+?\|.+?\|.+?\s*?\Z/', $reviews[$i])) {
                    $context->addViolationAt('reviews', "This value contains invalid formatting on line $line");
                }
            }
        }
    }
} 