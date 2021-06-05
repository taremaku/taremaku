<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\RoleFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class AppFixtures extends Fixture
{
    use ResetDatabase, Factories;

    public function load(ObjectManager $manager): void
    {
        RoleFactory::CreateOne(
            [
                'name' => 'Admin',
                'code' => 'ROLE_ADMIN',
            ]
        );

        RoleFactory::CreateOne(
            [
                'name' => 'User',
                'code' => 'ROLE_USER',
            ]
        );

        UserFactory::createMany(3, function () {
            return [
                'role' => RoleFactory::random()
            ];
        });
    }
}
