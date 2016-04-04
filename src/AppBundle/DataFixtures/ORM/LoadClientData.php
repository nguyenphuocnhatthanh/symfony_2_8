<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Client;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadClientData implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $client = new Client();
        $client->setRedirectUris(['http://localhost:8000']);
        $client->setAllowedGrantTypes([
            'authorization_code',
            'password',
            'refresh_token',
            'token',
            'client_credentials',
        ]);

        $manager->persist($client);
        $manager->flush();
    }
}