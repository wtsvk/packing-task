<?php

use App\Api\Parser\ProductCollectionParser;
use App\Api\Request\RequestParser;
use App\Application;
use App\Packaging\PackagingFinder;
use App\Packaging\PackagingStorage;
use App\Packaging\Provider\ApiPackingProvider;
use App\Packaging\Provider\DatabaseProvider;
use App\Packaging\Provider\FallbackProvider;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Swaggest\JsonSchema\Schema;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/** @var EntityManager $entityManager */
$entityManager = require __DIR__ . '/src/bootstrap.php';

$request = new Request('POST', new Uri('http://localhost/pack'), ['Content-Type' => 'application/json'], $argv[1]);


$encoders = [new JsonEncoder()];
$normalizers = [
    new ObjectNormalizer(null, null, null, new PhpDocExtractor()),
    new ArrayDenormalizer(),
];
$serializer = new Serializer($normalizers, $encoders);

$requestParser = new RequestParser($serializer);
$schemaContract = Schema::import(__DIR__ . '/resources/api/products_collection_request/schema.json');
$productRequestParser = new ProductCollectionParser($requestParser, $schemaContract);

$packagingStorage = new PackagingStorage($entityManager);
$packagingDatabaseProvider = new DatabaseProvider($packagingStorage);
$packagingApiProvider = new ApiPackingProvider();
$fallbackProvider = new FallbackProvider($entityManager);
$packagingFinder = new PackagingFinder($packagingStorage, [
    $packagingDatabaseProvider,
    $packagingApiProvider,
    $fallbackProvider,
]);

$application = new Application($serializer, $productRequestParser, $packagingFinder);
$response = $application->run($request);

echo "<<< In:\n" . Message::toString($request) . "\n\n";
echo ">>> Out:\n" . Message::toString($response) . "\n\n";
