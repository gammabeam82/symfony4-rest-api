<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\RestController;

use App\Tests\BaseTest;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group user
 */
class UserControllerTest extends BaseTest
{
    private const USER = [
        'username' => 'test',
        'email' => 'test@test.test',
        'password' => 'qwerty'
    ];

    private const LIST_SCHEMA = [
        'id' => 'integer',
        'username' => 'string',
        'avatar' => 'nullable|string'
    ];

    private const DETAILS_SCHEMA = [
        'id' => 'integer',
        'username' => 'string',
        'avatar' => 'nullable|string',
        'email' => 'string',
        'roles' => ['string'],
        'posts' => [
            'nullable' => true,
            'id' => 'integer',
            'title' => 'string'
        ]
    ];

    public function testCreateUser(): void
    {
        static::$client->request('POST', '/api/v1/users/', self::USER, [
            'avatar' => $this->getFile()
        ]);

        $this->assertEquals(Response::HTTP_CREATED, static::$client->getResponse()->getStatusCode());
    }

    public function testCreateUserWithError(): void
    {
        static::$client->request('POST', '/api/v1/users/', self::USER);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, static::$client->getResponse()->getStatusCode());
    }

    public function testLogin(): void
    {
        static::$client->request('POST', '/api/v1/login', [], [], [], json_encode(self::USER));

        $response = static::$client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('token', $content);
    }

    public function testGetList(): void
    {
        static::$client->request('GET', '/api/v1/users/', [], [], [
            'HTTP_Authorization' => static::$token
        ]);

        $response = static::$client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue(
            static::$schemaChecker->assertDataMatchesSchema($response->getContent(), self::LIST_SCHEMA),
            static::$schemaChecker->getViolations()
        );
    }

    public function testGetListWithError(): void
    {
        static::$client->request('GET', '/api/v1/users/');

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, static::$client->getResponse()->getStatusCode());
    }


    public function testGetSingleUser(): void
    {
        static::$client->request('GET', '/api/v1/users/1', [], [], [
            'HTTP_Authorization' => static::$token
        ]);

        $response = static::$client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue(
            static::$schemaChecker->assertDataMatchesSchema($response->getContent(), self::DETAILS_SCHEMA),
            static::$schemaChecker->getViolations()
        );
    }

    public function testUpdateAvatar(): void
    {
        static::$client->request('PATCH', '/api/v1/users/1/change_avatar', [], [
            'avatar' => $this->getFile()
        ], [
            'HTTP_Authorization' => static::$token
        ]);

        $this->assertEquals(Response::HTTP_OK, static::$client->getResponse()->getStatusCode());
    }

    public function testDeleteAvatar(): void
    {
        static::$client->request('DELETE', '/api/v1/users/1/delete_avatar', [], [], [
            'HTTP_Authorization' => static::$token
        ]);

        $this->assertEquals(Response::HTTP_OK, static::$client->getResponse()->getStatusCode());
    }

    public function testUpdateEmail(): void
    {
        $data = [
            'email' => 'test2@test.test'
        ];

        static::$client->request('PATCH', '/api/v1/users/1/change_email', [], [], [
            'HTTP_Authorization' => static::$token,
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($data));

        $this->assertEquals(Response::HTTP_OK, static::$client->getResponse()->getStatusCode());
    }

    public function testUpdatePassword(): void
    {
        $data = [
            'password' => 'zxcvbnm',
            'repeatedPassword' => 'zxcvbnm'
        ];

        static::$client->request('PATCH', '/api/v1/users/2/change_password', [], [], [
            'HTTP_Authorization' => $this->getToken(self::USER),
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($data));

        $this->assertEquals(Response::HTTP_OK, static::$client->getResponse()->getStatusCode());
    }

    public function testPromoteUser(): void
    {
        static::$client->request('PATCH', '/api/v1/users/2/promote', [], [], [
            'HTTP_Authorization' => static::$token
        ]);

        $this->assertEquals(Response::HTTP_OK, static::$client->getResponse()->getStatusCode());
    }

    public function testDemoteUser(): void
    {
        static::$client->request('PATCH', '/api/v1/users/2/demote', [], [], [
            'HTTP_Authorization' => static::$token
        ]);

        $this->assertEquals(Response::HTTP_OK, static::$client->getResponse()->getStatusCode());
    }

    public function testBlockUser(): void
    {
        static::$client->request('PATCH', '/api/v1/users/2/block', [], [], [
            'HTTP_Authorization' => static::$token
        ]);

        $this->assertEquals(Response::HTTP_OK, static::$client->getResponse()->getStatusCode());
    }

    public function testUnblockUser(): void
    {
        static::$client->request('PATCH', '/api/v1/users/2/unblock', [], [], [
            'HTTP_Authorization' => static::$token
        ]);

        $this->assertEquals(Response::HTTP_OK, static::$client->getResponse()->getStatusCode());
    }

    public function testDeleteUser(): void
    {
        static::$client->request('DELETE', '/api/v1/users/2', [], [], [
            'HTTP_Authorization' => static::$token
        ]);

        $this->assertEquals(Response::HTTP_OK, static::$client->getResponse()->getStatusCode());
    }
}
