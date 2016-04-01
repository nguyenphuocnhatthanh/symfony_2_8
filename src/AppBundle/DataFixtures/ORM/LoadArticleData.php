<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Article;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadArticleData implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $article =  new Article();
            $article->setTitle('Test '.$i);
            $article->setContent('Test test'.$i);
            $article->setLongtitude(20);
            $article->setLattitude(160);

            $manager->persist($article);
            $manager->flush();
        }

    }
}