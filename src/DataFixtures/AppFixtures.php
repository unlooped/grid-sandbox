<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\CustomerGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cg1 = new CustomerGroup();
        $cg1->setName('Default');
        $manager->persist($cg1);

        $cg2 = new CustomerGroup();
        $cg2->setName('VIP');
        $manager->persist($cg2);

        $cg3 = new CustomerGroup();
        $cg3->setName('Wholesale');
        $manager->persist($cg3);

        $groups = [$cg1, $cg2, $cg3];

        $faker = Factory::create();

        for ($i = 0; $i < 1000; $i++) {
            $customer = new Customer();
            $customer->setFirstName($faker->firstName());
            $customer->setLastName($faker->name);
            $customer->setCustomerGroup($groups[array_rand($groups)]);

            $manager->persist($customer);
        }

        $manager->flush();
    }
}
