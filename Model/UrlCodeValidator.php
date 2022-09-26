<?php
declare(strict_types=1);

namespace MageSuite\BaseUrlCode\Model;

class UrlCodeValidator
{
    public const PATTERN = '([a-z]{2})-([a-z]{2})';

    public function validate(string $urlCode): bool
    {
        return preg_match('#^' . self::PATTERN . '$#', $urlCode) === 1;
    }
}
