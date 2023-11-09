<?php

namespace App;

use App\Api\Parser\ProductCollectionParser;
use App\Api\Request\InvalidRequestException;
use App\Api\Request\RequestParser;
use App\Packaging\PackagingFinder;
use App\Packaging\PackagingStorage;
use App\Packaging\Provider\ApiPackingProvider;
use App\Packaging\Provider\DatabaseProvider;
use App\Packaging\Provider\FallbackProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Exception as DoctrineException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Swaggest\JsonSchema\Schema;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
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
