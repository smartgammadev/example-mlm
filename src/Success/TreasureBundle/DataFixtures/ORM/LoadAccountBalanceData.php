<?php

namespace Success\TreasureBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Success\TreasureBundle\Entity\AccountBalance;
use Success\TreasureBundle\Entity\AccountOperation;

class LoadAccountBalanceData extends AbstractFixture implements OrderedFixtureInterface
{

    private $fixtures = [
        [
            'memberExternalId' => '4success.bz@gmail.com',
            'balance' => 100,
        ],
        [
            'memberExternalId' => 'user_1-1@fake.domain',
            'balance' => 90,
        ],
        [
            'memberExternalId' => 'user_1-2@fake.domain',
            'balance' => 80,
        ],
        [
            'memberExternalId' => 'user_1-3@fake.domain',
            'balance' => 70,
        ],
        [
            'memberExternalId' => 'user_2-1@fake.domain',
            'balance' => 60,
        ],
        [
            'memberExternalId' => 'user_2-2@fake.domain',
            'balance' => 50,
        ],
        [
            'memberExternalId' => 'user_2-3@fake.domain',
            'balance' => 40,
        ],
        [
            'memberExternalId' => 'user_3-1@fake.domain',
            'balance' => 30,
        ],
        [
            'memberExternalId' => 'user_3-2@fake.domain',
            'balance' => 20,
        ],
        [
            'memberExternalId' => 'user_3-3@fake.domain',
            'balance' => 100,
        ],
        [
            'memberExternalId' => 'user_3-4@fake.domain',
            'balance' => 90,
        ],        
        [
            'memberExternalId' => 'user_3-5@fake.domain',
            'balance' => 80,
        ],        
        [
            'memberExternalId' => 'user_3-6@fake.domain',
            'balance' => 70,
        ],
        [
            'memberExternalId' => 'user_3-7@fake.domain',
            'balance' => 60,
        ],
        [
            'memberExternalId' => 'user_3-8@fake.domain',
            'balance' => 50,
        ],
        [
            'memberExternalId' => 'user_3-9@fake.domain',
            'balance' => 40,
        ],
        [
            'memberExternalId' => 'user_3-10@fake.domain',
            'balance' => 30,
        ],
        [
            'memberExternalId' => 'user_3-11@fake.domain',
            'balance' => 20,
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->fixtures as $fixture) {
            extract($fixture);
            $member = $manager->getRepository('SuccessMemberBundle:Member')->findOneBy(['externalId' => $memberExternalId]);
            
            $accountOperation = new AccountOperation();
            $accountOperation->setMember($member);
            $accountOperation->setDateOperation(new \DateTime());
            $accountOperation->setSubAccount('fixtures');
            $accountOperation->setAmount($balance);
            $manager->persist($accountOperation);
            
            $accountBalance = new AccountBalance();
            $accountBalance->setMember($member);
            $accountBalance->setAmount($balance);
            $manager->persist($accountBalance);
        }
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 10;
    }

}
