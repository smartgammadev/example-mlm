<?php

namespace Success\EventBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalendarControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/calendar');
        $this->assertTrue($crawler->filter('html:contains("calendarContainer")')->count() > 0);
    }
}
