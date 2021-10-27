<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Faker\Provider\ImutableDateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerFixtures extends Fixture
{
    private $hash;

    public function __construct(UserPasswordHasherInterface $hash)
    {
        $this->hash = $hash;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        for ($nbCustomer = 0; $nbCustomer <= 10; $nbCustomer++) {
            $customer = new Customer();
            $customer->setEmail($faker->companyEmail);
            $customer->setName($faker->company);
            $customer->setCreatedAt(ImutableDateTime::immutableDateTimeBetween());
            $customer->setPassword($this->hash->hashPassword($customer,'azertyazerty'));
            $this->addReference('customer_' . $nbCustomer, $customer);


            $manager->persist($customer);
        }

        $manager->flush();
    }
}
