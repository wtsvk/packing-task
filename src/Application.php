<?php

namespace App;

use App\Api\Parser\ProductCollectionParser;
use App\Api\Request\InvalidRequestException;
use App\Packaging\PackagingFinder;
use Doctrine\DBAL\Exception as DoctrineException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class Application
{
    public function __construct(
        private SerializerInterface $serializer,
        private ProductCollectionParser $productRequestParser,
        private PackagingFinder $packagingFinder,
    ) {}

    public function run(RequestInterface $request): ResponseInterface
    {
        try {
            $products = $this->productRequestParser->parse($request);
            $packaging = $this->packagingFinder->findPackaging($products);
        } catch (InvalidRequestException) {
            return new Response(400);
        } catch (GuzzleException) {
            return new Response(424);
        } catch (DoctrineException) {
            return new Response(503);
        }

        if ($packaging === null) {
            return new Response(404);
        }

        return new Response(body: $this->serializer->serialize($packaging, JsonEncoder::FORMAT));
    }

}
