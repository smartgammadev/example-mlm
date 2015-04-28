<?php

namespace Success\MemberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Success\MemberBundle\Entity\Member;

class MembersMigrateCommand extends ContainerAwareCommand
{
    const SPONSPOR_EMAIL_PLACEHOLDER = 'sponsor_email';

    protected function configure()
    {
        $this
                ->setName('success:members:migrate')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $memberManager = $this->getContainer()->get('success.member.member_manager');
        
        $memberRepo = $em->getRepository('SuccessMemberBundle:Member');
        $members = $memberRepo->findAll();
        
        $placeholderRepo = $em->getRepository('SuccessPlaceholderBundle:ExternalPlaceholder');
        $sponsorEmailPlaceholder =
            $placeholderRepo->findOneBy(['fullPattern' => self::SPONSPOR_EMAIL_PLACEHOLDER]);
        
        foreach ($members as $member) {
            $sponsorEmail = $memberManager->getMemberData($member, $sponsorEmailPlaceholder);
            try {
                $sponsor = $memberManager->getMemberByExternalId($sponsorEmail);
                $member->setSponsor($sponsor);
                echo $member->getExternalId() .' => '. $sponsor->getId().PHP_EOL;
            } catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $ex) {
                $sponsor = null;
                $member->setSponsor(null);
                echo $member->getExternalId().PHP_EOL;
            }
        }
        //$memberRepo->recover();
        $em->flush();
    }
}
