<?php declare(strict_types=1);

namespace Sergonie\Application\Exception;

class ConfigException extends ApplicationException
{
    public static function forExtractionFailure(string $key): self
    {
        return new self("Could not extract the key `{$key}` - extracted key must be an array.");
    }
}
