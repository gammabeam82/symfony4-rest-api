<?php

namespace App\Tests;

use Gammabeam82\SchemaChecker\SchemaChecker;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class BaseTest extends WebTestCase
{
    protected const ADMIN = [
        'username' => 'testuser',
        'password' => 'p@ssword'
    ];

    /**
     * @var Client
     */
    protected static $client;

    /**
     * @var string
     */
    protected static $token;

    /**
     * @var string
     */
    protected static $file;

    /**
     * @var SchemaChecker
     */
    protected static $schemaChecker;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        static::$client = static::createClient();
        static::$token = static::getToken(static::ADMIN);
        static::$file = static::$kernel->getContainer()->getParameter('upl_file');
        static::$schemaChecker = new SchemaChecker();
    }

    /**
     * @param array $credentials
     *
     * @return string
     */
    protected static function getToken(array $credentials): string
    {
        static::$client->request('POST', '/api/v1/login', [], [], [], json_encode($credentials));
        $content = json_decode(static::$client->getResponse()->getContent(), true);

        return array_key_exists('token', $content) ? sprintf("Bearer %s", $content['token']) : '';
    }

    /**
     * @return UploadedFile
     */
    protected function getFile(): UploadedFile
    {
        $tmp = tempnam(sys_get_temp_dir(), 'upl');
        copy(static::$file, $tmp);

        return new UploadedFile($tmp, '1.jpg', 'image/jpeg');
    }
}
