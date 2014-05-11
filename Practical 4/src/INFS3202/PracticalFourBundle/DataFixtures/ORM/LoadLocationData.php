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
use INFS3202\PracticalFourBundle\Entity\Location;
use INFS3202\PracticalFourBundle\Entity\Proprietor;

class LoadLocationData implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $proprietorRepository = $manager->getRepository("INFS3202PracticalFourBundle:Proprietor");
        $proprietor = $proprietorRepository->findOneBy(array('name' => 'A Salt & Battery'));

        $location = new Location();
        $location->setAddress("Hawken Village");
        $location->setProprietor($proprietor);

        $manager->persist($location);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 2;
    }
}