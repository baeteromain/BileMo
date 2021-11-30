<?php


namespace App\Tests\Unit;


use App\Entity\Customer;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = new User();
    }

    public function testGetEmail(): void
    {
        $value = "test@test.fr";
        $response = $this->user->setEmail($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getEmail());
    }

    public function testGetUsername(): void
    {
        $username = "test_username";
        $response = $this->user->setUsername($username);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($username, $this->user->getUserIdentifier());
    }

    public function testGetRoles(): void
    {
        self::assertContains('ROLE_USER', $this->user->getRoles());
    }

    public function testGetPassword(): void
    {
        $password = "azertyazerty";
        $response = $this->user->setPassword($password);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($password, $this->user->getPassword());
    }

    public function testGetUserDetails(): void
    {
        $details = [
            "firstname" => "TestFirstname",
            "lastname" => "TestLastname",
            "zipcode" => "testZipcode",
            "address" => "testAddress",
            "city" => "testCity",
            "phoneNumber" => "testPhoneNumber",
            "country" => "testCountry",
        ];

        $response = $this->user->setFirstname($details["firstname"]);
        self::assertInstanceOf(User::class, $response);
        self::assertEquals($details["firstname"], $this->user->getFirstname());

        $response = $this->user->setLastname($details["lastname"]);
        self::assertInstanceOf(User::class, $response);
        self::assertEquals($details["lastname"], $this->user->getLastname());

        $response = $this->user->setZipCode($details["zipcode"]);
        self::assertInstanceOf(User::class, $response);
        self::assertEquals($details["zipcode"], $this->user->getZipCode());

        $response = $this->user->setAddress($details["address"]);
        self::assertInstanceOf(User::class, $response);
        self::assertEquals($details["address"], $this->user->getAddress());

        $response = $this->user->setCity($details["city"]);
        self::assertInstanceOf(User::class, $response);
        self::assertEquals($details["city"], $this->user->getCity());

        $response = $this->user->setPhoneNumber($details["phoneNumber"]);
        self::assertInstanceOf(User::class, $response);
        self::assertEquals($details["phoneNumber"], $this->user->getPhoneNumber());

        $response = $this->user->setCountry($details["country"]);
        self::assertInstanceOf(User::class, $response);
        self::assertEquals($details["country"], $this->user->getCountry());
    }

    public function testGetCustomer(): void
    {
        $value = new Customer();

        $response = $this->user->setCustomer($value);
        self::assertInstanceOf(User::class, $response);
        self::assertInstanceOf(Customer::class, $this->user->getCustomer());
    }
}