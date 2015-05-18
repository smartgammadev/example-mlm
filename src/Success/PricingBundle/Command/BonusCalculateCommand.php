<?php

namespace Success\PricingBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Success\PricingBundle\Utils\DateRange;
use Success\PricingBundle\Entity\BonusCalculateShedule;
use JMS\JobQueueBundle\Entity\Job;

class BonusCalculateCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('success:bonus:calculate')
                ->setDescription('Calculates bonuses for members by BonusCalculationShedule id')
                ->addArgument('calculation-id', InputArgument::REQUIRED, 'Id of BonusCalculationShedule entity')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $calculationId = $input->getArgument('calculation-id');
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $calculationShedule = $em->getRepository('SuccessPricingBundle:BonusCalculateShedule')->findOneBy(['id' => $calculationId]);
        if ($calculationShedule === null) {
            return;
        }

        /* @var $memberManager \Success\MemberBundle\Service\MemberManager */
        $memberManager = $this->getContainer()->get('success.member.member_manager');

        /* @var $bonusCalculator \Success\PricingBundle\Service\BonusCalculator */
        $bonusCalculator = $this->getContainer()->get('success.pricing.bonus_calculator');

        $dateTo = new \DateTime();
        $dateFrom = new \DateTime();
        $days = $calculationShedule->getCalculationDays();
        $dateFrom->modify("- {$days} days");

        $output->writeln('Started bonus calculation:');
        $output->writeln('DateForm ->' . $dateFrom->format('Y-m-d H:i:s'));
        $output->writeln('DateTo ->' . $dateTo->format('Y-m-d H:i:s'));

        $dateRange = new DateRange($dateFrom, $dateTo);
        $members = $memberManager->getMembersForCalculateBonus($dateRange);

        $calculationResult = [];
        foreach ($members as $member) {
            $output->writeln(sprintf('Calculating bonus for member: id:%s, mail - "%s"', $member->getId(), $member->getExternalId()));
            $calculationResult[$member->getId()] = $bonusCalculator->calculateBonusForMember($member, $dateRange);
        }
        if ($calculationShedule->getAutoRecreate()) {
            $this->recreateBonusShedule($calculationShedule, $dateTo);
        }
        $calculationShedule->setIsProcessed(true);
        $calculationShedule->setCalculationResult($calculationResult);
        $em->flush();
    }

    private function recreateBonusShedule(BonusCalculateShedule $calculationShedule, \DateTime $startDate)
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $consoleCommand = 'success:bonus:calculate';

        $days = $calculationShedule->getCalculationDays();
        $newStartDate = clone $startDate;
        $newStartDate->modify("+ {$days} days");

        $newCalculationShedule = new BonusCalculateShedule();
        $newCalculationShedule->setAutoRecreate(true);
        $newCalculationShedule->setIsProcessed(false);
        $newCalculationShedule->setStartDate($newStartDate);
        $newCalculationShedule->setCalculationDays($calculationShedule->getCalculationDays());
        $em->persist($newCalculationShedule);
        $em->flush();

        $params = [$newCalculationShedule->getId()];
        $job = new Job($consoleCommand, $params);
        $job->setExecuteAfter($newStartDate);
        $newCalculationShedule->setJob($job);
        $em->persist($job);
        $em->flush();
    }
}
