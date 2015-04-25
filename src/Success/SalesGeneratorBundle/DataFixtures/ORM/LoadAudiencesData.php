<?php
namespace Success\SalesGeneratorBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\Persistence\ObjectManager;

use Success\SalesGeneratorBundle\Entity\Audience;



class AudienceFixture extends AbstractFixture implements OrderedFixtureInterface
{
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $metadata = $manager->getClassMetaData('Success\SalesGeneratorBundle\Entity\Audience');
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);        
        
        foreach ($this->audiences as $index => $audience) {
            $newAudience = new Audience();
            $newAudience->setId($index + 1);
            $newAudience->setName($audience);
            $manager->persist($newAudience);
            $this->addReference('SuccessAudience-'.($index + 1), $newAudience);
        }
        $manager->flush();
    }
    
    private $audiences = [
        'Сетевики (Группа в соцсети, посвященная какой-то МЛМ-компании)',
        'Заработок в Интернет (Группа в соцсетях, посвященная заработку)',
        'AltAutomatic - Теплый рынок 1 (приглашение через третье лицо)',
        'AltAutomatic - Теплый рынок 2 - Тест партнерской ссылки'
    ];
    
    public function getOrder()
    {
        return 1;
    }
}