<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testArray()
    {
        $data = [1, 2, 3 ,4];

        $this->assertContains(1, $data);
    }

    private function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals($statusCode,
            $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertCount(1, $crawler->filter('html:contains(Symfony)'));
    }

    public function testGetListArticle()
    {
        $crawler = $this->client->request(
            'GET',
            'http://localhost:8000/api/v1/articles',
            ['access_token' => 'MDM3MDE5MGNkYjY2MmExMzdmZTc1YTUwYjE4MTQ5ZmExYmJjYmY4ZmNhNmZjOWFlODY5MDkzOTE1YmEzZjI3Yg']
        );

        $this->assertJsonResponse($this->client->getResponse(), 200);
    }

    /**
     * @dataProvider provider_create
     */
    public function testCreateArticle()
    {
        
    }

    public function provider_create()
    {
        
    }
}
