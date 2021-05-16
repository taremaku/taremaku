<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\EpisodeFactory;
use App\Factory\RoleFactory;
use App\Factory\ShowFactory;
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
                'createdAt' => new \DateTimeImmutable(),
            ]
        );

        RoleFactory::CreateOne(
            [
                'name' => 'User',
                'code' => 'ROLE_USER',
                'createdAt' => new \DateTimeImmutable(),
            ]
        );

        UserFactory::createMany(3, function() {
            return [
                'createdAt' => new \DateTimeImmutable(),
                'role' => RoleFactory::random()
            ];
        });
    }
}
