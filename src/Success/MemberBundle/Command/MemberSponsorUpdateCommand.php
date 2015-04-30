<?php

namespace Success\MemberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Success\MemberBundle\Entity\Member;

class MemberSponsorUpdateCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('success:member:update_sponsor')
                ->addArgument(
                    'email',
                    InputArgument::OPTIONAL,
                    'For which member update sponsor'
                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $memberEmail = $input->getArgument('email');
        $this->updateSponsorForMemberFromAPI($memberEmail);
    }

    private function updateSponsorForMemberFromAPI($memberEmail)
    {
        /* @var $memberManager \Success\MemberBundle\Service\MemberManager */
        $memberManager = $this->getContainer()->get('success.member.member_manager');
        /* @var $tigerrHelper \Success\MemberBundle\Service\TigerrApiHelper */
        $tigerrHelper = $this->getContainer()->get('success.member.tigerr_helper');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $member = $memberManager->getMemberByExternalId($memberEmail);
        $sponsorEmail = $tigerrHelper->getSponsorEmail($memberEmail);
        $sponsor = $memberManager->getMemberByExternalId($sponsorEmail);
        $member->setSponsor($sponsor);

        $em->flush();
    }
}
