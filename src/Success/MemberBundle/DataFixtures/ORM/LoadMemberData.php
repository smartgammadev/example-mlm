<?php

namespace Success\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Success\MemberBundle\Entity\Member;

class LoadMemberData extends AbstractFixture implements OrderedFixtureInterface
{

    private $fixtures = [
        [
            'memberExternalId' => '4success.bz@gmail.com',
            'sponsorExternalId' => null,
        ],
        [
            'memberExternalId' => 'user_1-1@fake.domain',
            'sponsorExternalId' => '4success.bz@gmail.com',
        ],
        [
            'memberExternalId' => 'user_1-2@fake.domain',
            'sponsorExternalId' => '4success.bz@gmail.com',
        ],
        [
            'memberExternalId' => 'user_1-3@fake.domain',
            'sponsorExternalId' => '4success.bz@gmail.com',
        ],
        [
            'memberExternalId' => 'user_2-1@fake.domain',
            'sponsorExternalId' => 'user_1-1@fake.domain',
        ],
        [
            'memberExternalId' => 'user_2-2@fake.domain',
            'sponsorExternalId' => 'user_1-1@fake.domain',
        ],
        [
            'memberExternalId' => 'user_2-3@fake.domain',
            'sponsorExternalId' => 'user_1-1@fake.domain',
        ],
        [
            'memberExternalId' => 'user_3-1@fake.domain',
            'sponsorExternalId' => 'user_2-1@fake.domain',
        ],
        [
            'memberExternalId' => 'user_3-2@fake.domain',
            'sponsorExternalId' => 'user_2-1@fake.domain',
        ],
        [
            'memberExternalId' => 'user_3-3@fake.domain',
            'sponsorExternalId' => 'user_2-1@fake.domain',
        ],
        [
            'memberExternalId' => 'user_3-4@fake.domain',
            'sponsorExternalId' => 'user_2-2@fake.domain',
        ],        
        [
            'memberExternalId' => 'user_3-5@fake.domain',
            'sponsorExternalId' => 'user_2-2@fake.domain',
        ],        
        [
            'memberExternalId' => 'user_3-6@fake.domain',
            'sponsorExternalId' => 'user_2-2@fake.domain',
        ],
        [
            'memberExternalId' => 'user_3-7@fake.domain',
            'sponsorExternalId' => 'user_2-2@fake.domain',
        ],
        [
            'memberExternalId' => 'user_3-8@fake.domain',
            'sponsorExternalId' => 'user_2-2@fake.domain',
        ],
        [
            'memberExternalId' => 'user_3-9@fake.domain',
            'sponsorExternalId' => 'user_2-2@fake.domain',
        ],
        [
            'memberExternalId' => 'user_3-10@fake.domain',
            'sponsorExternalId' => 'user_2-2@fake.domain',
        ],
        [
            'memberExternalId' => 'user_3-11@fake.domain',
            'sponsorExternalId' => 'user_2-2@fake.domain',
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->fixtures as $fixture) {
            extract($fixture);
            $member = new Member();
            $member->setExternalId($memberExternalId);

            if ($sponsorExternalId) {
                $sponsor = $manager->getRepository('SuccessMemberBundle:Member')->findOneBy(['externalId' => $sponsorExternalId]);
                $member->setSponsor($sponsor);
            }
            $manager->persist($member);
            $manager->flush();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 0;
    }

}
