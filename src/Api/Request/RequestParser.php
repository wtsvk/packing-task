<?php

declare(strict_types=1);

namespace App\Api\Request;

use JsonException;
use Psr\Http\Message\RequestInterface;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\SchemaContract;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

use function json_decode;

final class RequestParser
{
    public function __construct(
        private SerializerInterface $serializer
    ) {}

    /**
     * @template T
     * @phpstan-param class-string<T> $type
     * @phpstan-return T
     * @return T
     */
    public function parse(RequestInterface $request, SchemaContract $contract, string $type): mixed
    {
        return $this->deserialize($request, $contract, $type);
    }

    private function deserialize(RequestInterface $request, SchemaContract $contract, string $type): mixed
    {
        try {
            $serialized = $request->getBody()->getContents();
            $objectData = json_decode($serialized, false, 512, JSON_THROW_ON_ERROR);
            $contract->in($objectData);
            $result = $this->serializer->deserialize($serialized, $type, JsonEncoder::FORMAT);
        } catch (InvalidValue $e) {
            throw InvalidRequestException::fromJsonSchemaException($e);
        } catch (JsonException $e) {
            throw InvalidRequestException::fromCoreJsonException($e);
        } catch (ExceptionInterface $e) {
            throw InvalidRequestException::fromSerializerException($e);
        }

        return $result;
    }
}
