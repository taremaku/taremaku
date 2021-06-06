<?php

declare(strict_types=1);


namespace App\Service;

class SlugService
{
    public function __construct(private bool $toLower)
    {
    }

    public function slugify(string $strToConvert): string
    {
        if ($this->toLower) {
            $strToConvert = strtolower($strToConvert);
        }

        $convertedString = preg_replace('/[^a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*/', '-', trim(strip_tags($strToConvert)));

        return $convertedString;
    }
}
