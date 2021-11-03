<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;

/**
 * Functional test for the controllers defined inside ApiControllerTest.
 *
 * See https://symfony.com/doc/current/testing.html#functional-tests
 *
 * Execute the application tests using this command (requires PHPUnit to be installed):
 *
 *     $ cd your-symfony-project/
 *     $ ./vendor/bin/phpunit
 */
class ApiControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->jsonRequest('GET', '/api/');

        $this->assertResponseIsSuccessful();

        $urls = json_decode($client->getResponse()->getContent());
        $this->assertCount(12, $urls);

        /** @var Router $router */
        $router = $this->getContainer()->get('router');
        $this->assertContains($router->generate('api_index', [], UrlGeneratorInterface::ABSOLUTE_URL), $urls);
        $this->assertContains($router->generate('api_my_info', [], UrlGeneratorInterface::ABSOLUTE_URL), $urls);
        $this->assertContains($router->generate('api_view', ['sku' => 'product_001'], UrlGeneratorInterface::ABSOLUTE_URL), $urls);
    }

    public function testIndexNonJsonRequest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/');

        $this->assertResponseStatusCodeSame(415);
    }

    public function testMyInfo(): void
    {
        $client = static::createClient();

        $dir = $this->getContainer()->getParameter('kernel.project_dir');
        @unlink($dir.'/var/log/my-info.log');

        $client->jsonRequest('GET', '/api/my-info');

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), \JSON_OBJECT_AS_ARRAY);

        $this->assertArrayHasKey('browser', $data);
        $this->assertNotEmpty($data['browser']);

        $this->assertArrayHasKey('ip', $data);
        $this->assertNotEmpty($data['ip']);

        $this->assertArrayHasKey('lang', $data);
        $this->assertNotEmpty($data['lang']);

        $this->assertFileExists($dir.'/var/log/test.log');
    }

    public function testProduct(): void
    {
        $client = static::createClient();
        $client->jsonRequest('GET', '/api/product_001');
        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), \JSON_OBJECT_AS_ARRAY);
        $this->assertArrayHasKey('sku', $data);
        $this->assertSame('product_001', $data['sku']);
        $this->assertArrayHasKey('name', $data);
        $this->assertSame('apple', $data['name']);
        $this->assertArrayHasKey('description', $data);
        $this->assertSame('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do', $data['description']);
    }

    public function testProductNotEmpty(): void
    {
        $client = static::createClient();
        $client->jsonRequest('GET', '/api/product_0111');
        $this->assertResponseStatusCodeSame(404);
    }
}
