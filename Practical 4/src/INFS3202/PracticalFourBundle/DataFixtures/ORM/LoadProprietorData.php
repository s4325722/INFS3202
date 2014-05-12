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
use INFS3202\PracticalFourBundle\Entity\Proprietor;

class LoadUserData implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $proprietor = new Proprietor();
        $proprietor->setName("A Salt & Battery");
        $proprietor->setPhone("");

        $manager->persist($proprietor);
        $manager->flush();

        $proprietor = new Proprietor();
        $proprietor->setName("Thai Tornado");
        $proprietor->setPhone("");

        $manager->persist($proprietor);
        $manager->flush();

        $proprietor = new Proprietor();
        $proprietor->setName("Gumby's Gumbo");
        $proprietor->setPhone("");

        $manager->persist($proprietor);
        $manager->flush();

        $proprietor = new Proprietor();
        $proprietor->setName("Massive Computers");
        $proprietor->setPhone("");

        $manager->persist($proprietor);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 1;
    }
}