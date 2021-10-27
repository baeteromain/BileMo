<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrandFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $brandsPhone = ["Apple", "Samsung", "Nokia"];

        foreach ($brandsPhone as $brandPhone) {
            $brand = new Brand();
            $brand->setName($brandPhone);
            $manager->persist($brand);

            $this->addReference('brand_' . $brandPhone, $brand);
        }
        $manager->flush();
    }
}
