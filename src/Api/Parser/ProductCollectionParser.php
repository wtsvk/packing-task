<?php

declare(strict_types=1);

namespace App\Api\Parser;

use App\Api\Request\RequestParser;
use App\Product\ProductCollection;
use Psr\Http\Message\RequestInterface;
use Swaggest\JsonSchema\SchemaContract;

class ProductCollectionParser
{
    private RequestParser $parser;

    private SchemaContract $schemaContract;

    public function __construct(RequestParser $parser, SchemaContract $schemaContract)
    {
        $this->parser = $parser;
        $this->schemaContract = $schemaContract;
    }

    public function parse(RequestInterface $request): ProductCollection
    {
        return $this->parser->parse($request, $this->schemaContract, ProductCollection::class);
    }
}
