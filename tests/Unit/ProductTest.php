<?php


namespace App\Tests\Unit;


use App\Entity\Brand;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->product = new Product();
    }

    public function testGetModel(): void
    {
        $model = "testModel";
        $response = $this->product->setModel($model);

        self::assertInstanceOf(Product::class, $response);
        self::assertEquals($model, $this->product->getModel());
    }

    public function testGetDescription(): void
    {
        $description = "testDescription";
        $response = $this->product->setDescription($description);

        self::assertInstanceOf(Product::class, $response);
        self::assertEquals($description, $this->product->getDescription());
    }

    public function testGetPrice(): void
    {
        $price = "123";
        $response = $this->product->setPrice($price);

        self::assertInstanceOf(Product::class, $response);
        self::assertEquals($price, $this->product->getPrice());
    }

    public function testGetQuantity(): void
    {
        $quantity = "1";
        $response = $this->product->setQuantity($quantity);

        self::assertInstanceOf(Product::class, $response);
        self::assertEquals($quantity, $this->product->getQuantity());
    }

    public function testGetBrand(): void
    {
        $value = new Brand();

        $response = $this->product->setBrand($value);
        self::assertInstanceOf(Product::class, $response);
        self::assertInstanceOf(Brand::class, $this->product->getBrand());

    }
}