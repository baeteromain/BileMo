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
        $emailCustomer = $this->ramdomCustomerEmail();

        $token = $this->getToken([
            'email' => $emailCustomer[0]['email'],
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
        $emailCustomer = $this->ramdomCustomerEmail();
        $token = $this->getToken([
            'email' => $emailCustomer[0]['email'],
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
        $emailCustomer = $this->ramdomCustomerEmail();
        $token = $this->getToken([
            'email' => $emailCustomer[0]['email'],
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
        $emailCustomer = $this->ramdomCustomerEmail();
        $token = $this->getToken([
            'email' => $emailCustomer[0]['email'],
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
        $idCustomer = $this->ramdomCustomerId();
        $response = $this->createClientWithCredentials()->request(
            'PUT',
            '/api/customers/' . $idCustomer[0]['id'],
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
        $emailCustomer = $this->ramdomCustomerEmail();
        $idCustomer = $this->ramdomCustomerId();
        $token = $this->getToken([
            'email' => $emailCustomer[0]['email'],
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request(
            'PUT',
            '/api/customers/' . $idCustomer[0]['id'],
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
        $iduser = $this->ramdomUserId();
        $response = $this->createClientWithCredentials()->request(
            'PUT',
            '/api/users/' . $iduser[0]['id'],
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
        $emailCustomer = $this->ramdomCustomerEmail();

        $customer = static::getContainer()->get(CustomerRepository::class)->findOneBy(['email' => $emailCustomer[0]['email']]);
        $users = static::getContainer()->get(UserRepository::class)->findBy(['customer' => $customer->getId()]);
        $AllUsers = static::getContainer()->get(UserRepository::class)->findAll();

        $all_user_id = [];
        foreach ($AllUsers as $userall) {
            $all_user_id[] = $userall->getId();
        }

        $user_id = [];
        foreach ($users as $user) {
            $user_id[] = $user->getId();
        }
        if (!empty($user_id)) {
            $key = array_rand($user_id);
            $id = $user_id[$key];
        }

        $noUserOfCustomer = array_diff($all_user_id, $user_id);

        $keyNoUserOfCustomer = array_rand($noUserOfCustomer);
        $noUserOfCustomer_id = $noUserOfCustomer[$keyNoUserOfCustomer];

        $token = $this->getToken([
            'email' => $emailCustomer[0]['email'],
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request(
            'PUT',
            '/api/users/' .  $noUserOfCustomer_id,
            [
                'body' => $this->getPayloadUser(),
                'headers' => ['Content-Type' => 'application/json'],
            ]
        );

        $this->assertResponseStatusCodeSame('403');
        $this->assertJsonContains([
            'hydra:description' => 'Can replace only your own users.',
        ]);

        if (!empty($user_id)) {

            $response = $this->createClientWithCredentials($token)->request(
                'PUT',
                '/api/users/' . $id,
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

    }

//    PATCH

    /**
     * @group PATCH
     */
    public function testAdminPatchCustomer()
    {
        $idCustomer = $this->ramdomCustomerId();
        $response = $this->createClientWithCredentials()->request(
            'PATCH',
            '/api/customers/' . $idCustomer[0]['id'],
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
        $emailCustomer = $this->ramdomCustomerEmail();
        $idCustomer = $this->ramdomCustomerId();
        $token = $this->getToken([
            'email' => $emailCustomer[0]['email'],
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request(
            'PATCH',
            '/api/customers/' . $idCustomer[0]['id'],
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
        $iduser = $this->ramdomUserId();
        $response = $this->createClientWithCredentials()->request(
            'PATCH',
            '/api/users/' . $iduser[0]['id'],
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
        $emailCustomer = $this->ramdomCustomerEmail();
        $customer = static::getContainer()->get(CustomerRepository::class)->findOneBy(['email' => $emailCustomer[0]['email']]);
        $users = static::getContainer()->get(UserRepository::class)->findBy(['customer' => $customer->getId()]);
        $AllUsers = static::getContainer()->get(UserRepository::class)->findAll();

        $all_user_id = [];
        foreach ($AllUsers as $userall) {
            $all_user_id[] = $userall->getId();
        }

        $user_id = [];
        foreach ($users as $user) {
            $user_id[] = $user->getId();
        }
        if (!empty($user_id)) {
            $key = array_rand($user_id);
            $id = $user_id[$key];
        }

        $noUserOfCustomer = array_diff($all_user_id, $user_id);

        $keyNoUserOfCustomer = array_rand($noUserOfCustomer);
        $noUserOfCustomer_id = $noUserOfCustomer[$keyNoUserOfCustomer];

        $token = $this->getToken([
            'email' => $emailCustomer[0]['email'],
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request(
            'PATCH',
            '/api/users/' . $noUserOfCustomer_id,
            [
                'body' => '{"username" : "PatchNameCusto}',
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseStatusCodeSame('403');
        $this->assertJsonContains([
            'hydra:description' => 'Can update only your own users.',
        ]);

        if (!empty($user_id)) {
            $response = $this->createClientWithCredentials($token)->request(
                'PATCH',
                '/api/users/' . $id,
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

    }

//    DELETE

    /**
     * @group DELETE
     */
    public function testAdminDeleteCustomer()
    {
        $idCustomer = $this->ramdomCustomerId();
        $response = $this->createClientWithCredentials()->request('DELETE', '/api/customers/' . $idCustomer[0]['id']);


        $this->assertResponseStatusCodeSame('204');
        $this->assertNull(static::getContainer()->get(CustomerRepository::class)->findOneBy(['id' => $idCustomer[0]['id']]));

    }

    /**
     * @group DELETE
     */
    public function testCustomerDeleteCustomer()
    {
        $emailCustomer = $this->ramdomCustomerEmail();
        $idCustomer = $this->ramdomCustomerId();

        $token = $this->getToken([
            'email' => $emailCustomer[0]['email'],
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request('DELETE', '/api/customers/' . $idCustomer[0]['id']);
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
        $iduser = $this->ramdomUserId();

        $response = $this->createClientWithCredentials()->request('DELETE', '/api/users/' . $iduser[0]['id']);

        $this->assertResponseStatusCodeSame('204');
        $this->assertNull(static::getContainer()->get(UserRepository::class)->findOneBy(['id' => $iduser[0]['id']]));

    }

    /**
     * @group DELETE22
     */
    public function testCustomerDeleteUser()
    {
        $emailCustomer = $this->ramdomCustomerEmail();

        $customer = static::getContainer()->get(CustomerRepository::class)->findOneBy(['email' => $emailCustomer[0]['email']]);
        $users = static::getContainer()->get(UserRepository::class)->findBy(['customer' => $customer->getId()]);
        $AllUsers = static::getContainer()->get(UserRepository::class)->findAll();

        $all_user_id = [];
        foreach ($AllUsers as $userall) {
            $all_user_id[] = $userall->getId();
        }

        $user_id = [];
        foreach ($users as $user) {
            $user_id[] = $user->getId();
        }
        if (!empty($user_id)) {
            $key = array_rand($user_id);
            $id = $user_id[$key];
        }

        $noUserOfCustomer = array_diff($all_user_id, $user_id);

        $keyNoUserOfCustomer = array_rand($noUserOfCustomer);
        $noUserOfCustomer_id = $noUserOfCustomer[$keyNoUserOfCustomer];

        $token = $this->getToken([
            'email' => $emailCustomer[0]['email'],
            'password' => 'azertyazerty',
        ]);

        $response = $this->createClientWithCredentials($token)->request('DELETE', '/api/users/' . $noUserOfCustomer_id);
        $this->assertResponseStatusCodeSame('403', 'Access Denied.');
        $this->assertJsonContains([
            'hydra:description' => 'Can delete only your own users.',
        ]);

        if (!empty($user_id)) {
            $response = $this->createClientWithCredentials($token)->request('DELETE', '/api/users/' . $id);

            $this->assertResponseStatusCodeSame('204');
            $this->assertNull(static::getContainer()->get(UserRepository::class)->findOneBy(['id' => $id]));
        }

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

    private function ramdomCustomerEmail()
    {
        return static::getContainer()->get(CustomerRepository::class)->getRandomCustomerEmail();
    }

    private function ramdomCustomerId()
    {
        return static::getContainer()->get(CustomerRepository::class)->getRandomCustomerId();
    }

    private function ramdomUserId()
    {
        return static::getContainer()->get(UserRepository::class)->getRandomUserId();
    }

}
