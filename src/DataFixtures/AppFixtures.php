<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->getConnection()->exec(
            file_get_contents('/var/www/symfony_docker/symfony_docker.sql')
        );

        $manager->flush();
    }
}
