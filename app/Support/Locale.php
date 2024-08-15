<?php

namespace Vanguard\Support;

class Locale
{
    public const AVAILABLE_LOCALES = ['ru','en', 'de', 'sr'];

    public static function flagUrl(string $locale): ?string
    {
        return match ($locale) {
            'ru' => url('/flags/RU.png'),
            'en' => url('/flags/GB.png'),
            'de' => url('/flags/DE.png'),
            'sr' => url('/flags/RS.png'),
            default => null,
        };
    }

    public static function validateLocale(string $locale): bool
    {
        return in_array($locale, self::AVAILABLE_LOCALES);
    }
}
