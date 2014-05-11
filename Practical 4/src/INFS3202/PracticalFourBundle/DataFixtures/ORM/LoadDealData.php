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
use INFS3202\PracticalFourBundle\Entity\Category;
use INFS3202\PracticalFourBundle\Entity\Proprietor;
use INFS3202\PracticalFourBundle\Entity\Deal;

class LoadDealData implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $categoryRepository = $manager->getRepository("INFS3202PracticalFourBundle:Category");
        $proprietorRepository = $manager->getRepository("INFS3202PracticalFourBundle:Proprietor");

        $category = $categoryRepository->findOneBy(array('name' => 'Food'));
        $proprietor = $proprietorRepository->findOneBy(array('name' => 'A Salt & Battery'));

        $startDate = new \DateTime('now');

        $endDate = clone $startDate;
        $endDate->add(new \DateInterval('P1D'));

        $deal = new Deal();
        $deal->setCategory($category);
        $deal->setProprietor($proprietor);
        $deal->setTitle("Fishy Fridays!");
        $deal->setDescription("But one mackerel, get one free");
        $deal->setPrice(10.00);
        $deal->setBanner("http://example.com");
        $deal->setTimestampStart($startDate);
        $deal->setTimestampEnd($endDate);

        $manager->persist($deal);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 4;
    }
}