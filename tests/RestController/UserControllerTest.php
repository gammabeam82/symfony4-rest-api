<?php

namespace App\Tests\RestController;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserControllerTest extends WebTestCase
{
    private const USER = [
        'username' => 'test',
        'email' => 'test@test.test',
        'password' => 'qwerty'
    ];

    /**
     * @var Client
     */
    private $client;


    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    private function getToken(array $credentials): string
    {
        $this->client->request('POST', '/api/v1/login', [], [], [], json_encode($credentials));
        $content = json_decode($this->client->getResponse()->getContent(), true);

        return array_key_exists('token', $content) ? sprintf("Bearer %s", $content['token']) : '';
    }

    private function getMockFile(): UploadedFile
    {
        $container = self::$kernel->getContainer();
        $tmp = tempnam(sys_get_temp_dir(), 'upl');
        copy($container->getParameter('upl_file'), $tmp);

        return new UploadedFile($tmp, '1.jpg');
    }

    public function testCreateUser(): void
    {
        $this->client->request('POST', '/api/v1/users/register', self::USER);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateUserWithError(): void
    {
        $this->client->request('POST', '/api/v1/users/register', self::USER);

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testLogin(): void
    {
        $this->client->request('POST', '/api/v1/login', [], [], [], json_encode(self::USER));

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('token', $content);
    }

    public function testGetSingleUser(): void
    {
        $this->client->request('GET', '/api/v1/users/1', [], [], [
            'HTTP_Authorization' => $this->getToken(self::USER)
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetList(): void
    {
        $this->client->request('GET', '/api/v1/users/', [], [], [
            'HTTP_Authorization' => $this->getToken(self::USER)
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetListWithError(): void
    {
        $this->client->request('GET', '/api/v1/users/', [], [], []);

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateEmail(): void
    {
        $data = ['email' => 'test2@test.test'];

        $this->client->request('PATCH', '/api/v1/users/2/change_email', [], [], [
            'HTTP_Authorization' => $this->getToken(self::USER)
        ], json_encode($data));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateAvatar(): void
    {
        $this->client->request('PATCH', '/api/v1/users/2/change_avatar', [], [
            'imagefile' => $this->getMockFile()
        ], [
            'HTTP_Authorization' => $this->getToken(self::USER)
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdatePassword(): void
    {
        $data = [
            'password' => 'zxcvbnm',
            'repeatedPassword' => 'zxcvbnm'
        ];

        $this->client->request('PATCH', '/api/v1/users/2/change_password', [], [], [
            'HTTP_Authorization' => $this->getToken(self::USER)
        ], json_encode($data));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteUser(): void
    {
        $credentials = [
            'username' => 'testuser',
            'password' => 'p@ssword'
        ];

        $this->client->request('DELETE', '/api/v1/users/2', [], [], [
            'HTTP_Authorization' => $this->getToken($credentials)
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
