<?php

namespace App\DataFixtures;

use App\Entity\User as EntityUser;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class User extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface  $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {        
        $admin = new EntityUser();
        $admin->setEmail('daryazezina@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin,'123'));
        $admin->setFirstName('Daria');
        $admin->setLastName('Zezina');
        $admin->setPhone('+375256721616');
        $manager->persist($admin);

        $user = new EntityUser();
        $user->setEmail('test_user@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user,'123'));
        $user->setFirstName('Test');
        $user->setLastName('User');
        $user->setPhone('+375298888888');
        $manager->persist($user);

        $manager->flush();
    }
}
