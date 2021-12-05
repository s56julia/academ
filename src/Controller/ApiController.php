<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Notification\NotificationChannelRegistry;
use App\Notification\UnknownChannelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/api', name: 'api_')]
class ApiController
{
    #[Route('/', name: 'index')]
    public function index(UrlGeneratorInterface $urlGenerator): Response
    {
        $urls = [];
        $urls[] = $urlGenerator->generate('api_index', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $urls[] = $urlGenerator->generate('api_my_info', [], UrlGeneratorInterface::ABSOLUTE_URL);
        foreach (array_keys($this->getProductsData()) as $sku) {
            $urls[] = $urlGenerator->generate('api_view', ['sku' => $sku], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return new JsonResponse($urls);
    }

    #[Route('/notify', name: 'notify', methods: ['POST'])]
    public function notify(Request $request, NotificationChannelRegistry $channelRegistry): Response
    {
        $recipient = $request->request->get('recipient');
        $message = $request->request->get('message');
        $channel = $request->request->get('channel');

        if (empty($channel)) {
            throw new NotFoundHttpException('Channel is required');
        }

        try {
            $transport = $channelRegistry->getNotificationTransportByChannel($channel);
        } catch (UnknownChannelException $e) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        if (!$transport->send($recipient, $message)) {
            return new Response(null, Response::HTTP_I_AM_A_TEAPOT);
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/my-info', name: 'my_info', priority: 10)]
    public function info(Request $request, TranslatorInterface $translator): Response
    {
        $ua = $request->server->get('HTTP_USER_AGENT');
        $lang = $request->server->get('HTTP_ACCEPT_LANGUAGE');
        $ip = $request->getClientIp();

        $response = new JsonResponse([
            $translator->trans('browser') => $ua,
            $translator->trans('ip') => $ip,
            $translator->trans('lang') => $lang,
        ]);
        $logDir = realpath($this->getVarDirPath().\DIRECTORY_SEPARATOR.'log');
        file_put_contents($logDir.\DIRECTORY_SEPARATOR.'my-info.log', $response->getContent().\PHP_EOL, \FILE_APPEND);

        return $response;
    }

    #[Route('/{sku}', name: 'view')]
    public function view(string $sku, TranslatorInterface $translator): Response
    {
        $products = $this->getProductsData();
        if (!\array_key_exists($sku, $products)) {
            throw new NotFoundHttpException('Unknown sku requested');
        }
        $row = $products[$sku];

        return new JsonResponse([
            $translator->trans('sku') => $row['sku'],
            $translator->trans('name') => $row['name'],
            $translator->trans('description') => $row['description'],
        ]);
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
}
