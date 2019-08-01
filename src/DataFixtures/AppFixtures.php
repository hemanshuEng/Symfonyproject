<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\MicroPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->loadUser($manager);
        $this->loadMicroPosts($manager);
    }
    private function loadMicroPosts(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < 10; $i++) {
            $microPost = new MicroPost();
            $microPost->setText('some Random Text' . rand(0, 100));
            $microPost->setTime(new \DateTime('2018-03-15'));
            $microPost->setUser($this->getReference('Hemanshu_Khodiyar'));
            $manager->persist($microPost);
        }
        $manager->flush();
    }
    private function loadUser(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('hemanshu87');
        $user->setFullname('Hemanshu Khodiyar');
        $user->setEmail('hemanshu@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'hem123'));
        $this->addReference('Hemanshu_Khodiyar', $user);
        $manager->persist($user);
        $manager->flush();
    }
}
