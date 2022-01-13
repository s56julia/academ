<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductImage;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiProductController
{
    #[Route('/product/images', name: 'product_images', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        /** @var ProductImage[] $productImages */

        $page= (int)$request->query->get('page', 1);
        $perPage= (int)$request->query->get('perPage', 20);

        $productImages = $doctrine->getRepository(ProductImage::class)->getImagesWithProducts($page, $perPage);

        $imagesData = $this->getImagesData($productImages);

        return new JsonResponse($imagesData);
    }

    /**
     * @param iterable|Product[] $products
     * @return array
     */
    protected function productToArray(iterable $products): array
    {
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'title' => $product->getTitle(),
                'created_at' => $product->getCreatedAt()
            ];
        }

        return $data;
    }

    /**
     * @param array|ProductImage[] $productImages
     * @return array
     */
    protected function getImagesData(array $productImages): array
    {
        $imagesData = [];
        foreach ($productImages as $image) {
            $imagesData[] = [
                'id' => $image->getId(),
                'title' => $image->getTitle(),
                'created_at' => $image->getCreatedAt()->format(\DateTime::ATOM),
                'products' => $this->productToArray($image->getProducts())
            ];
        }

        return $imagesData;
    }
}
