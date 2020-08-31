<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ProductService
 * @package App\Service
 */
class ProductService
{
    /** @var EntityManagerInterface  */
    private $entityManager;

    /**
     * CartController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Product[] $products
     * @return array
     */
    public function getProductsForOutput($products): array
    {
        $productsOutput = [];
        foreach ($products as $product) {
            $productsOutput [] = $this->getProductForOutput($product);
        }
        return $productsOutput;
    }

    /**
     * @param Product $product
     * @return array
     */
    public function getProductForOutput(Product $product): array
    {
        return [
            'uuid' => $product->getUuid(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'currency' => $product->getCurrency(),
            'createdAt' => $product->getCreatedAt()
        ];
    }
}