<?php


namespace App\Tests\Unit;


use App\Entity\Customer;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = new Customer();
    }

    public function testGetEmail(): void
    {
        $value = "testcustomer@test.fr";
        $response = $this->customer->setEmail($value);

        self::assertInstanceOf(Customer::class, $response);
        self::assertEquals($value, $this->customer->getEmail());
        self::assertEquals($value, $this->customer->getUserIdentifier());
    }


    public function testGetRoles(): void
    {
        $value = ['ROLE_ADMIN'];

        $response = $this->customer->setRoles($value);

        self::assertInstanceOf(Customer::class, $response);
        self::assertContains('ROLE_USER', $this->customer->getRoles());
        self::assertContains('ROLE_ADMIN', $this->customer->getRoles());
    }

    public function testGetPassword(): void
    {
        $password = "adminadmin";
        $response = $this->customer->setPassword($password);

        self::assertInstanceOf(Customer::class, $response);
        self::assertEquals($password, $this->customer->getPassword());
    }

    public function testGetName(): void
    {
        $name = "testName";
        $response = $this->customer->setName($name);

        self::assertInstanceOf(Customer::class, $response);
        self::assertEquals($name, $this->customer->getName());
    }

    public function testGetUser(): void
    {
        $value = new User();

        $response = $this->customer->addUser($value);

        self::assertInstanceOf(Customer::class, $response);
        self::assertContains($value, $this->customer->getUser());
        self::assertCount(1, $this->customer->getUser());
        self::assertTrue($this->customer->getUser()->contains($value));

        $response = $this->customer->removeUser($value);

        self::assertInstanceOf(Customer::class, $response);
        self::assertCount(0, $this->customer->getUser());
        self::assertFalse($this->customer->getUser()->contains($value));

    }
}