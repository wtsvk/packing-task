<?php

declare(strict_types=1);

namespace App\Api\Request;

use JsonException;
use Swaggest\JsonSchema\InvalidValue;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Throwable;
use UnexpectedValueException;

class InvalidRequestException extends UnexpectedValueException
{
    public string $error = '';

    final public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null, string $error = '')
    {
        parent::__construct($message, $code, $previous);
        $this->error = $error;
    }

    public static function fromJsonSchemaException(InvalidValue $e): static
    {
        return new static($e->getMessage(), $e->getCode(), $e, $e->error);
    }

    public static function fromCoreJsonException(JsonException $e): static
    {
        return new static($e->getMessage(), $e->getCode(), $e, $e->getMessage());
    }

    public static function fromSerializerException(ExceptionInterface $e): static
    {
        return new static($e->getMessage(), $e->getCode(), $e, $e->getMessage());
    }
}
