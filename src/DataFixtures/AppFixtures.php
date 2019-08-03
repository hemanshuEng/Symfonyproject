<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\MicroPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const USERS = [
        [
            'username' => 'jhon_doe',
            'email' => 'john_doe@doe.com',
            'password' => 'john123',
            'fullname' => 'John Doe',
            'roles'    => [User::ROLE_USER]
        ],
        [
            'username' => 'rod_smith',
            'email' => 'rob_smiths@smith.com',
            'password' => 'rob123',
            'fullname' => 'Rob Smith',
            'roles'    => [User::ROLE_USER]
        ],
        [
            'username' => 'marry_gold',
            'email' => 'marry_gold@gold.com',
            'password' => 'marry1234',
            'fullname' => 'Marry Gold',
            'roles'    => [User::ROLE_USER]
        ],
        [
            'username' => 'super_admin',
            'email' => 'super_admin@admin.com',
            'password' => 'admin1234',
            'fullname' => 'Super Admin',
            'roles'    => [User::ROLE_ADMIN]
        ],
    ];

    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];

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
        for ($i = 0; $i < 30; $i++) {
            $microPost = new MicroPost();
            $microPost->setText(self::POST_TEXT[rand(0, count(self::POST_TEXT) - 1)]);
            $date = new \DateTime();
            $date->modify('-' . rand(0, 10) . 'day');
            $microPost->setTime($date);
            $microPost->setUser($this->getReference(self::USERS[rand(0, count(self::USERS) - 1)]['username']));
            $manager->persist($microPost);
        }
        $manager->flush();
    }
    private function loadUser(ObjectManager $manager)
    {
        foreach (self::USERS as $userData) {

            $user = new User();
            $user->setUsername($userData['username']);
            $user->setFullname($userData['fullname']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userData['password']));
            $user->setRoles($userData['roles']);
            $this->addReference($userData['username'], $user);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
