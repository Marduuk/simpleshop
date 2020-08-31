<?php
declare(strict_types=1);

namespace App\Tests\Product;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ProductGetTest
 * @package App\Tests\Product
 */
class ProductGetTest extends WebTestCase
{
    public function testProductDeletePositive(): void
    {
        $client = static::createClient();
        $client->request('GET', 'http://127.0.0.1:8000/product');

        $data = json_decode($client->getResponse()->getContent(), true);

        $previous = $data['products'][0];
        foreach ($data['products'] as $product){
            $this->assertTrue($previous['createdAt'] >= $product['createdAt']);
            $previous = $product;
        }

        $this->assertTrue($data['success']);
        $this->assertCount(10, $data['products']);
        $this->assertResponseStatusCodeSame($client->getResponse()->getStatusCode());
    }
}