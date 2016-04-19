<?php

namespace tests\BatteryBundle;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;

/**
 * Class StatisticControllerTest
 * @package tests\BatteryBundle
 */
class StatisticControllerTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Client
     */
    private $client;

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $link = $crawler->selectLink('here')->link();
        $this->client->click($link);
        $this->assertContains('Add new batteries',
            $this->client->getResponse()->getContent()
        );
    }

    public function testAdd()
    {
        $crawler = $this->client->request('GET', '/add');
        $form = $crawler->selectButton('Add battery')->form();

        $form['battery[type]']  = 'AAA';
        $form['battery[count]'] = '5';
        $form['battery[name]']  = 'Alex';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();
        $this->assertContains('Welcome to Recycle Batteries application',
            $this->client->getResponse()->getContent()
        );
        $this->assertEquals(
            $crawler->filter('td:contains("AAA")')->siblings()->text(),
            5
        );
    }

    public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine')->getManager();
    }

    public function tearDown()
    {
        $this->em->getRepository('BatteryBundle:Battery')->removeAll();
    }
}
