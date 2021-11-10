<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/api', name: 'api_')]
class ApiController
{
    #[Route('/', name: 'index')]
    public function index(UrlGeneratorInterface $urlGenerator, Request $request): Response
    {
        $this->checkContentType($request);

        $urls = [];
        $urls[] = $urlGenerator->generate('api_index', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $urls[] = $urlGenerator->generate('api_my_info', [], UrlGeneratorInterface::ABSOLUTE_URL);
        foreach (array_keys($this->getProductsData()) as $sku) {
            $urls[] = $urlGenerator->generate('api_view', ['sku' => $sku], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return new JsonResponse($urls);
    }

    #[Route('/my-info', name: 'my_info', priority: 10)]
    public function info(Request $request): Response
    {
        $this->checkContentType($request);

        $ua = $request->server->get('HTTP_USER_AGENT');
        $lang = $request->server->get('HTTP_ACCEPT_LANGUAGE');
        $ip = $request->getClientIp();

        $response = new JsonResponse(['browser' => $ua, 'ip' => $ip, 'lang' => $lang]);
        $logDir = realpath($this->getVarDirPath().\DIRECTORY_SEPARATOR.'log');
        file_put_contents($logDir.\DIRECTORY_SEPARATOR.'my-info.log', $response->getContent().\PHP_EOL, \FILE_APPEND);

        return $response;
    }

    #[Route('/{sku}', name: 'view')]
    public function view(string $sku, Request $request): Response
    {
        $this->checkContentType($request);

        $products = $this->getProductsData();
        if (!\array_key_exists($sku, $products)) {
            throw new NotFoundHttpException('Unknown sku requested');
        }

        return new JsonResponse($products[$sku]);
    }

    protected function getVarDirPath(): string
    {
        return realpath(__DIR__.'/../../var');
    }

    protected function getProductsData(): array
    {
        $logDir = $this->getVarDirPath().\DIRECTORY_SEPARATOR.'data';
        $handle = fopen($logDir.\DIRECTORY_SEPARATOR.'products.csv', 'r');
        $headerRow = fgetcsv($handle);

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            $row = array_combine($headerRow, $data);
            $rows[$row['sku']] = $row;
        }
        fclose($handle);

        return $rows;
    }

    protected function checkContentType(Request $request): void
    {
        if ('application/json' !== $request->headers->get('Content-Type')) {
            throw new UnsupportedMediaTypeHttpException();
        }
    }
}
