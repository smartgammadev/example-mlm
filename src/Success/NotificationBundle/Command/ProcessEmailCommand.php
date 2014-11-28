<?php
namespace Success\NotificationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class ProcessEmailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('notify:process_email')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = 'Processing mail';
        $nm = $this->getContainer()->get('success.notification.notification_manager');
        $nm->processEmailNotifications();
        $output->writeln($text);
    }
}
