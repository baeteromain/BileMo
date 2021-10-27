<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Faker\Provider\ImutableDateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $hash;

    public function __construct(UserPasswordHasherInterface $hash)
    {
        $this->hash = $hash;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        for ($nbUser = 0; $nbUser <= 30; $nbUser++) {
            $user = new User();
            $user->setEmail($faker->companyEmail);
            $user->setUsername($faker->userName);
            $user->setCreatedAt(ImutableDateTime::immutableDateTimeBetween());
            $user->setPassword($this->hash->hashPassword($user,'azertyazerty'));
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setZipCode($faker->postcode);
            $user->setAddress($faker->streetAddress);
            $user->setCity($faker->city);
            $user->setCountry('FRANCE');
            $user->setPhoneNumber($faker->phoneNumber);
            $user->setCustomer($this->getReference('customer_' . $faker->numberBetween(1, 10)));

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CustomerFixtures::class,
        ];
    }
}
