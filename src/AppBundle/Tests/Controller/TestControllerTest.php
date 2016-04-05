<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestControllerTest extends WebTestCase
{
    public function testArray()
    {
        $data = [1, 2, 3 ,4];

        $this->assertContains(1, $data);
    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertCount(1, $crawler->filter('html:contains(Symfony)'));
    }

    public function testGetListArticle()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'GET',
            'http://localhost:8000/api/v1/articles',
            ['access_token' => 'MjEyM2VjYWFmMDk3NTBjZTdiMThkZmY1NTQ3NDA1ZDcxNTdkNDI1NTExZGFkZWZhMDgwN2VmNjI5M2RmOWE5OQ']
        );

        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-type',
            'application/json'
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
