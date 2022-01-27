<?php

namespace App\EntityGenerator;

use App\Entity\Product;
use App\Entity\ProductImage;
use Doctrine\Persistence\ManagerRegistry;

class EntityGenerator
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function generateEntity(int $productsCount, int $productImagesCount)
    {
        $entityManager = $this->doctrine->getManagerForClass(ProductImage::class);

        $productImages = [];
        for ($i = 0; $i < $productImagesCount; $i++) {
            $productImage = new ProductImage();
            $productImage->setTitle('image_' . $i);
            $entityManager->persist($productImage);

            $productImages[] = $productImage;
        }

        for ($i = 0; $i < $productsCount; $i++) {
            $product = new Product();
            $product->setTitle('product_' . $i);
            $product->setImage(($this->getRandomProductImage($productImages)));

            $entityManager->persist($product);
        }

        $entityManager->flush();
    }

    private function getRandomProductImage(array $productImage): ProductImage
    {
        return $productImage[array_rand($productImage)];
    }
}
