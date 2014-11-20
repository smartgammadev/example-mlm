<?php
namespace Application\Sonata\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\MediaBundle\Entity\Media;



class LoadMediaData extends AbstractFixture implements OrderedFixtureInterface {
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $newMedia = new Media();        
        $newMedia->setProviderName('sonata.media.provider.image');
        $newMedia->setContext('webinar_image');
        $newMedia->setName('webinar_image');
        $newMedia->setBinaryContent(__DIR__.'/../../../../../../web/uploads/webinar_image.jpg');        
        $manager->persist($newMedia);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}