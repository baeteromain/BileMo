<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Product;
use App\Faker\Provider\ImutableDateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;


class ProductFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $modelSamsung = ['S20', 'S10+'];
        $modelApple = ['X', 'XR'];
        $modelNokia = ['3310', 'N97'];

        foreach ($modelSamsung as $model) {
            $phone = new Product();
            $phone->setModel($model);
            $phone->setCreatedAt(ImutableDateTime::immutableDateTimeBetween());
            $phone->setDescription($faker->realText(180));
            $phone->setPrice($faker->numberBetween(148, 360));
            $phone->setQuantity($faker->numberBetween(0, 40));
            $phone->setBrand($this->getReference('brand_Samsung'));
            $manager->persist($phone);
        }

        foreach ($modelApple as $model) {
            $phone = new Product();
            $phone->setModel($model);
            $phone->setCreatedAt(ImutableDateTime::immutableDateTimeBetween());
            $phone->setDescription($faker->realText(180));
            $phone->setPrice($faker->numberBetween(148, 360));
            $phone->setQuantity($faker->numberBetween(0, 40));
            $phone->setBrand($this->getReference('brand_Apple'));
            $manager->persist($phone);
        }

        foreach ($modelNokia as $model) {
            $phone = new Product();
            $phone->setModel($model);
            $phone->setCreatedAt(ImutableDateTime::immutableDateTimeBetween());
            $phone->setDescription($faker->realText(180));
            $phone->setPrice($faker->numberBetween(148, 360));
            $phone->setQuantity($faker->numberBetween(0, 40));
            $phone->setBrand($this->getReference('brand_Nokia'));
            $manager->persist($phone);
        }


        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BrandFixtures::class,
        ];
    }
}


