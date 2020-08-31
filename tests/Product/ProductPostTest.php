<?php
declare(strict_types=1);

namespace App\Tests\Product;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ProductPostTest
 * @package App\Tests\Product
 */
class ProductPostTest extends WebTestCase
{
    public function testProductPostPositive(): void
    {
        $client = static::createClient();
        $em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $client->request('POST', 'http://127.0.0.1:8000/login', [
            'username' => 'admin',
            'password' => 'admin'
        ]);

        $productsName = 'supertestnameefkjwifeiweinkalsfdfue';
        $client->request('POST', 'http://127.0.0.1:8000/admin/product', [
            'name' => $productsName,
            'description' => 'LOREM PIPSUMLOREM
             PIPSUMLOREM PIPSUMLOREM PIPSUMLOREM PIPSUMLOREM PIPSUMLOREM PIPSUMLOREM
              PIPSUMLOREM PIPSUMLOREM PIPSUMLOREM PIPSUMLOREM PIPSUMLOREM PIPSUMLOREM 
              PIPSUMLOREM PIPSUMLOREM PIPSUMLOREM PIPSUMLOREM PIPSUM',
            'price' => 142
        ]);

        $data = json_decode($client->getResponse()->getContent(), true);

        $productRepository = $em->getRepository(Product::class);
        $product = $productRepository->findOneBy(['name' => $productsName]);

        $this->assertNotNull($product);
        $this->assertTrue($data['success']);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $em->remove($product);
        $em->flush();
    }
}