<?php

namespace Success\MemberBundle\Tests\Services;

class TigerrApiHelperTest extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{

    protected $instance;
    
    protected function setUp()
    {
        $this->instance = $this->createClient()->getKernel()->getContainer()->get('success.member.tigerr_helper');
    }
    
    public function testGetSponsorEmail()
    {
        $result = $this->instance->getSponsorEmail('rregion1292@mail.ru');
        $this->assertNotNull($result);
        $this->assertEquals('string', gettype($result));
    }
}
