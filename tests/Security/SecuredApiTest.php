<?php
declare(strict_types=1);

namespace App\Tests\Security;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecuredApiTest
 * @package App\Tests\Security
 */
class SecuredApiTest extends WebTestCase
{
    public function testAddNewProductWithoutLogging(): void
    {
        $client = static::createClient();
        $client->request('POST', 'http://127.0.0.1:8000/admin/product');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertResponseStatusCodeSame(401);
    }
}