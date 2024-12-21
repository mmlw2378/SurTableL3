<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $article = new Article();
            $article->setNom('Article ' . $i)
                    ->setPrix(mt_rand(10, 100))
                    ->setQuantiteDisponible(mt_rand(1, 50));
            $manager->persist($article);
        }
        $manager->flush();
    }
}
