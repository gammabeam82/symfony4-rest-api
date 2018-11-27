<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\RestController;

use App\Tests\BaseTest;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group tag
 */
class TagControllerTest extends BaseTest
{
    private const SCHEMA = [
        'id' => 'integer',
        'name' => 'string',
        'posts' => [
            'id' => 'integer',
            'title' => 'string'
        ]
    ];

    private const NULLABLE_POSTS_SCHEMA = [
        'id' => 'integer',
        'name' => 'string',
        'posts' => [
            'nullable' => true,
            'id' => 'integer',
            'title' => 'string'
        ]
    ];

    public function testCreateTag(): void
    {
        $data = [
            'name' => 'test'
        ];

        static::$client->request('POST', '/api/v1/tags/', [], [], [
            'HTTP_Authorization' => static::$token,
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($data));

        $this->assertEquals(Response::HTTP_CREATED, static::$client->getResponse()->getStatusCode());
    }

    public function testCreateTagWithError(): void
    {
        $data = [
            'name' => 'test'
        ];

        static::$client->request('POST', '/api/v1/tags/', [], [], [
            'HTTP_Authorization' => static::$token,
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($data));

        $this->assertEquals(Response::HTTP_BAD_REQUEST, static::$client->getResponse()->getStatusCode());
    }

    public function testGetList(): void
    {
        static::$client->request('GET', '/api/v1/tags/', [], [], [
            'HTTP_Authorization' => static::$token
        ]);

        $response = static::$client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue(
            static::$schemaChecker->assertDataMatchesSchema($response->getContent(), self::NULLABLE_POSTS_SCHEMA),
            static::$schemaChecker->getViolations()
        );
    }

    public function testGetSingleTag(): void
    {
        static::$client->request('GET', '/api/v1/tags/1', [], [], [
            'HTTP_Authorization' => static::$token
        ]);

        $response = static::$client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue(
            static::$schemaChecker->assertDataMatchesSchema($response->getContent(), self::SCHEMA),
            static::$schemaChecker->getViolations()
        );
    }

    public function testUpdateTag(): void
    {
        $data = [
            'name' => 'test11'
        ];

        static::$client->request('PATCH', '/api/v1/tags/5', [], [], [
            'HTTP_Authorization' => static::$token,
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($data));

        $this->assertEquals(Response::HTTP_OK, static::$client->getResponse()->getStatusCode());
    }

    public function testDeleteTag(): void
    {
        static::$client->request('DELETE', '/api/v1/tags/5', [], [], [
            'HTTP_Authorization' => static::$token
        ]);

        $this->assertEquals(Response::HTTP_OK, static::$client->getResponse()->getStatusCode());
    }
}
