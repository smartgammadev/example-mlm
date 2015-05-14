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
            ->addArgument('calculation_id', InputArgument::REQUIRED, 'Id of bonus calculation entity')
        ;
    }
    
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $calculationId = $input->getArgument('calculation_id');
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $calculationShedule = $em->getRepository('SuccessPricingBundle:BonusCalculateShedule')->findOneBy(['id' => $calculationId]);        
        if ($calculationShedule === null) {
            return;
        }
        
        
        
        var_dump($calculationShedule->getJob());
        die;
    }
}
