<?php


namespace App\Tests\Func;


use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Faker\Factory;

final class CustomerTest extends AbstractTest
{
    private string $customerPayload = '{
    "email" : "%s",
    "password" : "%s",
    "name" : "%s"
    }';

    private string $userPayload = '{
    "username" : "%s",
    "email" : "%s",
    "password" : "%s",
    "firstname" : "%s",
    "lastname" : "%s",
    "address" : "%s",
    "city" : "%s",
    "zipcode" : "%s",
    "phone" : "%s",
    "country" : "%s",
    "customer_uri" : "%s"
    }';

//    GET

    /**
     * @group GET
     */
    public function testLoginAsAdminToGetCustomer()
    {
        $response = $this->createClientWithCredentials()->request('GET', '/api/customers');
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);
        $this->assertResponseIsSuccessful();
        $this->assertJson($responseContent);
        $this->assertNotEmpty($responseDecoded);
    }

    /**
     * @group GET
     */
    public function testLoginAsCustomerToGetCustomer()
    {
        $token = $this->getToken([
            'email' => 'ekris@medhurst.org',
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request('GET', '/api/customers');
        $this->assertResponseStatusCodeSame('403', 'Access Denied.');
        $this->assertJsonContains([
            'hydra:description' => 'Only admins can view all customers.',
        ]);
    }

    /**
     * @group GET
     */
    public function testLoginAsUserToGetCustomer()
    {
        $client = self::createClient();
        $response = $client->request('GET', '/api/customers');
        $this->assertResponseStatusCodeSame('401', 'JWT Token not found');
    }


    /**
     * @group GET
     */
    public function testAdminCustomerGetUser()
    {
        $response = $this->createClientWithCredentials()->request(
            'GET',
            '/api/users',
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        $this->assertResponseStatusCodeSame('200');
        $this->assertResponseIsSuccessful();
        $this->assertJson($responseContent);
        $this->assertNotEmpty($responseDecoded);

    }

    /**
     * @group GET
     */
    public function testCustomerGetUser()
    {
        $token = $this->getToken([
            'email' => 'ekris@medhurst.org',
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request(
            'GET',
            '/api/users',
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        $this->assertResponseStatusCodeSame('200');
        $this->assertResponseIsSuccessful();
        $this->assertJson($responseContent);
        $this->assertNotEmpty($responseDecoded);

    }

//    POST

    /**
     * @group POST
     */
    public function testAdminPostCustomer()
    {
        $response = $this->createClientWithCredentials()->request(
            'POST',
            '/api/customers',
            [
                'body' => $this->getPayloadCustomer(),
                'headers' => ['Content-Type' => 'application/json'],
            ]
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        $this->assertResponseStatusCodeSame('201');
        $this->assertJson($responseContent);
        $this->assertNotEmpty($responseDecoded);
    }

    /**
     * @group POST
     */
    public function testCustomerPostCustomer()
    {
        $token = $this->getToken([
            'email' => 'ekris@medhurst.org',
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request(
            'POST',
            '/api/customers',
            [
                'body' => $this->getPayloadCustomer(),
                'headers' => ['Content-Type' => 'application/json'],
            ]
        );

        $this->assertResponseStatusCodeSame('403');
        $this->assertJsonContains([
            'hydra:description' => 'Only admins can add customers.',
        ]);
    }

    /**
     * @group POST
     */
    public function testAdminPostUser()
    {
        $response = $this->createClientWithCredentials()->request(
            'POST',
            '/api/users',
            [
                'body' => $this->getPayloadUser(),
                'headers' => ['Content-Type' => 'application/json'],
            ]
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        $this->assertResponseStatusCodeSame('201');
        $this->assertJson($responseContent);
        $this->assertNotEmpty($responseDecoded);

    }

    /**
     * @group POST
     */

    public function testCustomerPostUser()
    {
        $token = $this->getToken([
            'email' => 'ekris@medhurst.org',
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request(
            'POST',
            '/api/users',
            [
                'body' => $this->getPayloadUser(),
                'headers' => ['Content-Type' => 'application/json'],
            ]
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        $this->assertResponseStatusCodeSame('201');
        $this->assertJson($responseContent);
        $this->assertNotEmpty($responseDecoded);

    }

//    PUT

    /**
     * @group PUT
     */
    public function testAdminPutCustomer()
    {
        $response = $this->createClientWithCredentials()->request(
            'PUT',
            '/api/customers/6',
            [
                'body' => $this->getPayloadCustomer(),
                'headers' => ['Content-Type' => 'application/json'],
            ]
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        $this->assertResponseStatusCodeSame('200');
        $this->assertJson($responseContent);
        $this->assertNotEmpty($responseDecoded);

    }

    /**
     * @group PUT
     */

    public function testCustomerPutCustomer()
    {
        $token = $this->getToken([
            'email' => 'ekris@medhurst.org',
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request(
            'PUT',
            '/api/customers/6',
            [
                'body' => $this->getPayloadCustomer(),
                'headers' => ['Content-Type' => 'application/json'],
            ]
        );

        $this->assertResponseStatusCodeSame('403');
        $this->assertJsonContains([
            'hydra:description' => 'Only admins can replace customers.',
        ]);
    }

    /**
     * @group PUT
     */
    public function testAdminPutUser()
    {
        $response = $this->createClientWithCredentials()->request(
            'PUT',
            '/api/users/6',
            [
                'body' => $this->getPayloadUser(),
                'headers' => ['Content-Type' => 'application/json'],
            ]
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        $this->assertResponseStatusCodeSame('200');
        $this->assertJson($responseContent);
        $this->assertNotEmpty($responseDecoded);

    }

    /**
     * @group PUT
     */

    public function testCustomerPutUser()
    {
        $token = $this->getToken([
            'email' => 'ekris@medhurst.org',
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request(
            'PUT',
            '/api/users/13',
            [
                'body' => $this->getPayloadUser(),
                'headers' => ['Content-Type' => 'application/json'],
            ]
        );

        $this->assertResponseStatusCodeSame('403');
        $this->assertJsonContains([
            'hydra:description' => 'Can replace only your own users.',
        ]);

        $response = $this->createClientWithCredentials($token)->request(
            'PUT',
            '/api/users/33',
            [
                'body' => $this->getPayloadUser(),
                'headers' => ['Content-Type' => 'application/json'],
            ]
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        $this->assertResponseStatusCodeSame('200');
        $this->assertJson($responseContent);
        $this->assertNotEmpty($responseDecoded);

    }

//    PATCH

    /**
     * @group PATCH
     */
    public function testAdminPatchCustomer()
    {
        $response = $this->createClientWithCredentials()->request(
            'PATCH',
            '/api/customers/6',
            [
                'body' => '{"name" : "put3Name"}',
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
            ]
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        $this->assertResponseStatusCodeSame('200');
        $this->assertJson($responseContent);
        $this->assertNotEmpty($responseDecoded);

    }

    /**
     * @group PATCH
     */

    public function testCustomerPatchCustomer()
    {
        $token = $this->getToken([
            'email' => 'ekris@medhurst.org',
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request(
            'PATCH',
            '/api/customers/6',
            [
                'body' => '{"name" : "put4Name"}',
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame('403');
        $this->assertJsonContains([
            'hydra:description' => 'Only admins can update customers.',
        ]);

    }

    /**
     * @group PATCH
     */
    public function testAdminPatchUser()
    {
        $response = $this->createClientWithCredentials()->request(
            'PATCH',
            '/api/users/6',
            [
                'body' => '{"username" : "patchUsername"}',
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
            ]
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        $this->assertResponseStatusCodeSame('200');
        $this->assertJson($responseContent);
        $this->assertNotEmpty($responseDecoded);

    }

    /**
     * @group PATCH
     */

    public function testCustomerPatchUser()
    {
        $token = $this->getToken([
            'email' => 'ekris@medhurst.org',
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request(
            'PATCH',
            '/api/users/6',
            [
                'body' => '{"username" : "PatchNameCusto}',
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame('403');
        $this->assertJsonContains([
            'hydra:description' => 'Can update only your own users.',
        ]);

        $response = $this->createClientWithCredentials($token)->request(
            'PATCH',
            '/api/users/33',
            [
                'body' => '{"username" : "PatchNameCusto"}',
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
            ]
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        $this->assertResponseStatusCodeSame('200');
        $this->assertJson($responseContent);
        $this->assertNotEmpty($responseDecoded);

    }

//    DELETE

    /**
     * @group DELETE
     */
    public function testAdminDeleteCustomer()
    {
        $response = $this->createClientWithCredentials()->request('DELETE', '/api/customers/11');

        $this->assertResponseStatusCodeSame('204');
        $this->assertNull(static::getContainer()->get(CustomerRepository::class)->findOneBy(['id' => '11']));

    }

    /**
     * @group DELETE
     */
    public function testCustomerDeleteCustomer()
    {
        $token = $this->getToken([
            'email' => 'ekris@medhurst.org',
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request('DELETE', '/api/customers/11');
        $this->assertResponseStatusCodeSame('403', 'Access Denied.');
        $this->assertJsonContains([
            'hydra:description' => 'Only admins can delete customers.',
        ]);
    }

    /**
     * @group DELETE
     */
    public function testAdminDeleteUser()
    {
        $response = $this->createClientWithCredentials()->request('DELETE', '/api/users/11');

        $this->assertResponseStatusCodeSame('204');
        $this->assertNull(static::getContainer()->get(UserRepository::class)->findOneBy(['id' => '11']));

    }

    /**
     * @group DELETE
     */
    public function testCustomerDeleteUser()
    {
        $token = $this->getToken([
            'email' => 'ekris@medhurst.org',
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request('DELETE', '/api/users/11');
        $this->assertResponseStatusCodeSame('403', 'Access Denied.');
        $this->assertJsonContains([
            'hydra:description' => 'Can delete only your own users.',
        ]);

        $response = $this->createClientWithCredentials($token)->request('DELETE', '/api/users/33');

        $this->assertResponseStatusCodeSame('204');
        $this->assertNull(static::getContainer()->get(UserRepository::class)->findOneBy(['id' => '33']));
    }

    private function getPayloadCustomer(): string
    {
        $faker = Factory::create();

        return sprintf($this->customerPayload, $faker->email, password_hash("azertyazerty", PASSWORD_BCRYPT), "testName_" . $faker->randomNumber());
    }

    private function getPayloadUser(): string
    {
        $faker = Factory::create('fr_FR');

        return sprintf(
            $this->userPayload,
            $faker->userName,
            $faker->email,
            "azertyazerty",
            $faker->firstName(),
            $faker->lastName,
            $faker->streetAddress,
            $faker->city,
            $faker->postcode,
            $faker->phoneNumber,
            $faker->country,
            "15");
    }
}
