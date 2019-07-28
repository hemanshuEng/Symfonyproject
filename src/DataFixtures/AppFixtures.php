<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\MicroPost;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < 10; $i++) {
            $microPost = new MicroPost();
            $microPost->setText('some Random Text' . rand(0, 100));
            $microPost->setTime(new \DateTime('2018-03-15'));
            $manager->persist($microPost);
        }
        $manager->flush();
    }
}
