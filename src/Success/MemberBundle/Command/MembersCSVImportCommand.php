<?php

namespace Success\MemberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Success\MemberBundle\Entity\Member;
use Symfony\Component\Finder\Finder;

class MembersCSVImportCommand extends ContainerAwareCommand
{

    private $csvParsingOptions = array(
        'finder_in' => 'app/Resources/',
        'finder_name' => 'contacts_export.csv',
        'ignoreFirstLine' => true
    );

    protected function configure()
    {
        $this
                ->setName('success:members:csv_import')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importedRows = $this->parseCSV();
        foreach ($importedRows as $key => $row) {
            echo 'Processing row::'.$key.' - '.$row[3];
            $this->createMember($row[3]);
            echo '<<< done.'.PHP_EOL;
        }
    }
    
    private function createMember($externalId)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
        $newMember = new Member();
        $newMember->setExternalId($externalId);
        $em->persist($newMember);
        $em->flush();
    }
    
    
    
    /**
     * Parse a csv file
     * @return array
     */
    private function parseCSV()
    {
        $ignoreFirstLine = $this->csvParsingOptions['ignoreFirstLine'];

        $finder = new Finder();
        $finder->files()
                ->in($this->csvParsingOptions['finder_in'])
                ->name($this->csvParsingOptions['finder_name'])
        ;
        foreach ($finder as $file) {
            $csv = $file;
        }
        $rows = array();
        if (($handle = fopen($csv->getRealPath(), "r")) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, null, ",")) !== false) {
                $i++;
                if ($ignoreFirstLine && $i == 1) {
                    continue;
                }
                $rows[] = $data;
            }
            fclose($handle);
        }

        return $rows;
    }
}
