<?php
namespace Application\Sonata\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\UserBundle\Entity\User;



class LoadUserData extends AbstractFixture implements OrderedFixtureInterface {
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $newAdmin = new User();
        $newAdmin->setUsername('admin');
        $newAdmin->setEmail('admin@4success');
        $newAdmin->setPlainPassword('admin');
        $newAdmin->setSuperAdmin(true);
        $newAdmin->setEnabled(true);
        $manager->persist($newAdmin);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
