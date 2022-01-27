<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api', name: 'api_')]
class ProductController extends AbstractController
{
    #[Route('/product', name: 'create_product', methods: ['POST'])]
    public function createProduct(Request $request, ValidatorInterface $validator, ManagerRegistry $doctrine): Response {

        $titleProduct = $request->request->get('title');

        if (empty($titleProduct)) {
            throw new NotFoundHttpException('Product title is required');
        }

        $entityManager = $doctrine->getManagerForClass(Product::class);

        $product = new Product();
        $product->setTitle($titleProduct);
        $product->setAuthor($this->getUser());

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $entityManager->persist($product);
        $entityManager->flush();

        return new JsonResponse(null);
    }
}
