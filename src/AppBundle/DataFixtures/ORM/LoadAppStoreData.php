<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\AppStore;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadAppStoreData implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $appStore =  new AppStore();
            $appStore->setName('App Store '.$i);

            $manager->persist($appStore);
            $manager->flush();
        }
    }
}