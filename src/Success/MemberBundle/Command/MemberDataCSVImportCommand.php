<?php
namespace Success\MemberBundle\Command;

use Success\MemberBundle\Entity\Member;
use Success\MemberBundle\Entity\MemberData;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class MemberDataCSVImportCommand extends ContainerAwareCommand
{
    const EMAIL_COLUMN_INDEX = 3;
    
    private $csvColumn2PlaceholderMapping = [
        0 => 'user_first_name',
        1 => 'user_last_name',
        2 => 'user_country',
        3 => 'user_email',
        4 => 'user_skype',
        5 => 'user_phone',
        7 => 'user_city',
        18 => 'user_vkontakte',
        19 => 'user_facebook',
        20 => 'user_twitter',
    ];
    
    
    private $csvParsingOptions = array(
        'finder_in' => 'app/Resources/',
        'finder_name' => 'contacts_export.csv',
        'ignoreFirstLine' => true
    );

    protected function configure()
    {
        $this
                ->setName('success:member_data:csv_import')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importedRows = $this->parseCSV();
        foreach ($importedRows as $key => $row) {
            echo 'Processing row::'.$key.' - '.$row[self::EMAIL_COLUMN_INDEX].PHP_EOL;
            $this->updateMemberData($row);
            echo '<<< done.'.PHP_EOL;
        }
    }
    
    private function updateMemberData($row)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
        /* @var $placeholderManager \Success\PlaceholderBundle\Service\PlaceholderManager */
        $placeholderManager = $this->getContainer()->get('success.placeholder.placeholder_manager');
        
        /* @var $memberManager \Success\MemberBundle\Service\MemberManager */
        $memberManager = $this->getContainer()->get('success.member.member_manager');

        /* @var $tigerrHelper \Success\MemberBundle\Service\TigerrApiHelper */
        $tigerrHelper = $this->getContainer()->get('success.member.tigerr_helper');
        
        foreach ($this->csvColumn2PlaceholderMapping as $index => $placeholderName) {
            if ($row[$index] != '') {
                /* @var $member \Success\MemberBundle\Entity\Member */
                $member = $memberManager->getMemberByExternalId($row[self::EMAIL_COLUMN_INDEX]);
                
                $sponsorEmail = $tigerrHelper->getSponsorEmail($row[self::EMAIL_COLUMN_INDEX]);
                if ($sponsorEmail) {
                    $sponsor = $memberManager->getMemberByExternalId($sponsorEmail);
                    $member->setSponsor($sponsor);
                }
                
                $placeholder = $placeholderManager->resolveExternalPlaceholder($placeholderName);
                $memberData = new MemberData();
                $memberData->setPlaceholder($placeholder);
                $memberData->setMember($member);
                $memberData->setMemberData($row[$index]);
                $em->persist($memberData);
            }
        }
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
