<?php


namespace App\Tests\Unit;


use App\Entity\Brand;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class BrandTest extends TestCase
{
    private Brand $brand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->brand = new Brand();
    }

    public function testGetName(): void
    {
        $name = "testName";
        $response = $this->brand->setName($name);

        self::assertInstanceOf(Brand::class, $response);
        self::assertEquals($name, $this->brand->getName());
    }

    public function testGetProduct(): void
    {
        $value = new Product();

        $response = $this->brand->addProduct($value);

        self::assertInstanceOf(Brand::class, $response);
        self::assertContains($value, $this->brand->getProduct());
        self::assertCount(1, $this->brand->getProduct());
        self::assertTrue($this->brand->getProduct()->contains($value));

        $response = $this->brand->removeProduct($value);

        self::assertInstanceOf(Brand::class, $response);
        self::assertCount(0, $this->brand->getProduct());
        self::assertFalse($this->brand->getProduct()->contains($value));

    }
}