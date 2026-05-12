<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $usersData = [
            [
                'username' => 'Alice Admin',
                'email'    => 'admin@blogtech.com',
                'roles'    => ['ROLE_ADMIN'],
                'password' => 'admin1234',
                'ref'      => 'user_admin',
            ],
            [
                'username' => 'Bob User',
                'email'    => 'bob@blogtech.com',
                'roles'    => ['ROLE_USER'],
                'password' => 'user1234',
                'ref'      => 'user_user',
            ],
            [
                'username' => 'Charlie',
                'email'    => 'charlie@blogtech.com',
                'roles'    => [],
                'password' => 'noone1234',
                'ref'      => 'user_no_role',
            ],
        ];

        foreach ($usersData as $data) {
            $user = (new User())
                ->setUsername($data['username'])
                ->setEmail($data['email'])
                ->setRoles($data['roles'])
                ->setCreatedAt(new \DateTimeImmutable());

            $user->setPassword(
                $this->hasher->hashPassword($user, $data['password'])
            );

            $manager->persist($user);
            $this->addReference($data['ref'], $user);
        }

        $manager->flush();
    }
}