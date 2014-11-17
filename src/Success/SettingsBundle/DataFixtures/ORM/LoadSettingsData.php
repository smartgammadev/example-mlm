<?php
namespace Success\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Success\SettingsBundle\Entity\Setting;


class LoadSettingsData extends AbstractFixture implements OrderedFixtureInterface {
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $minutesToVisitEvent = new Setting();
        $minutesToVisitEvent->setName('minutesToVisitEvent');
        $minutesToVisitEvent->setSettingValue('20');
        $manager->persist($minutesToVisitEvent);
        
        $beforeEventDateModifier = new Setting();
        $beforeEventDateModifier->setName('beforeEventDateModifier');
        $beforeEventDateModifier->setSettingValue('30');
        $manager->persist($beforeEventDateModifier);
        
        $userSignUpEmailMessage = new Setting();
        $userSignUpEmailMessage->setName('userSignUpEmailMessage');
        $userSignUpEmailMessage->setSettingValue('Здраствуйте, {{user_first_name}} {{user_last_name}}! Информируем Вас о том, что Вы записаны на вебинар. С уважением команда 4Success.');
        $manager->persist($userSignUpEmailMessage);
        
        $sponsorSignUpEmailMessage = new Setting();
        $sponsorSignUpEmailMessage->setName('sponsorSignUpEmailMessage');
        $sponsorSignUpEmailMessage->setSettingValue('Здраствуйте, {{sponsor_first_name}} {{sponsor_last_name}}! Информируем Вас о том, что пользователь {{user_first_name}} {{user_last_name}} записался по Вашей ссылке на вебинар. С уважением команда 4Success.');
        $manager->persist($sponsorSignUpEmailMessage);
        
        $sponsorSignUpSMSMessage = new Setting();
        $sponsorSignUpSMSMessage->setName('sponsorSignUpSMSMessage');
        $sponsorSignUpSMSMessage->setSettingValue('Пользователь {{user_first_name}} {{user_last_name}} записался по Вашей ссылке на вебинар ');
        $manager->persist($sponsorSignUpSMSMessage);
        
        $userBeforeEventSMSMessage = new Setting();
        $userBeforeEventSMSMessage->setName('userBeforeEventSMSMessage');
        $userBeforeEventSMSMessage->setSettingValue('Напоминаем Вам о том, что Вы записаны на вебинар который состоится через 30 минут.');
        $manager->persist($userBeforeEventSMSMessage);
        
        $userBeforeEventEmailMessage = new Setting();
        $userBeforeEventEmailMessage->setName('userBeforeEventEmailMessage');
        $userBeforeEventEmailMessage->setSettingValue('Здраствуйте, {{user_first_name}} {{user_last_name}}! Напоминаем Вам о том, что Вы записаны на вебинар который состоится через 30 минут. С уважением команда 4Success.');
        $manager->persist($userBeforeEventEmailMessage);

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
