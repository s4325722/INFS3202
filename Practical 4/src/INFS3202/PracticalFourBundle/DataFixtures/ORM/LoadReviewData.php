<?php
/**
 * Created by PhpStorm.
 * User: Blake
 * Date: 11/05/2014
 * Time: 11:50 PM
 */

namespace INFS3202\PracticalFourBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use INFS3202\PracticalFourBundle\Entity\Deal;
use INFS3202\PracticalFourBundle\Entity\Review;

class LoadReviewData implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $dealRepository = $manager->getRepository("INFS3202PracticalFourBundle:Deal");
        $deal = $dealRepository->findOneBy(array('title' => 'Fishy Fridays!'));

        $review = new Review();
        $review->setDeal($deal);
        $review->setTitle("A little fishy...");
        $review->setDescription("I thought it was good value until I spent the next day with food poisoning!");
        $review->setAuthor("Herp Derpinson");
        $review->setScore(0.1);
        $review->setTimestamp(new \DateTime());

        $manager->persist($review);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 5;
    }
}