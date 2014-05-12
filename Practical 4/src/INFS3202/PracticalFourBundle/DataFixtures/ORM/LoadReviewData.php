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

        $deal = $dealRepository->findOneBy(array('title' => "Thai Tuesdays"));
        $review = new Review();
        $review->setDeal($deal);
        $review->setTitle("Tasty Thai!");
        $review->setDescription("The best Thai I've ever eaten!");
        $review->setAuthor("Derp Herpinson");
        $review->setScore(0.9);
        $review->setTimestamp(new \DateTime());

        $manager->persist($review);
        $manager->flush();

        $deal = $dealRepository->findOneBy(array('title' => "Gooble Gumbo"));
        $review = new Review();
        $review->setDeal($deal);
        $review->setTitle("Did someone say Gumbo?");
        $review->setDescription("Right from the deep south, it's delicious.");
        $review->setAuthor("John Johnson");
        $review->setScore(0.7);
        $review->setTimestamp(new \DateTime());

        $manager->persist($review);
        $manager->flush();

        $deal = $dealRepository->findOneBy(array('title' => "Hello Hal"));
        $review = new Review();
        $review->setDeal($deal);
        $review->setTitle("Very unhelpful!!!111");
        $review->setDescription("These guys don't seem to have any idea what they're doing!");
        $review->setAuthor("Dicklan Dickinson");
        $review->setScore(0.25);
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