<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\Services;

use Cat4year\SvgReuser\Sanitizer\Sanitizer;
use enshrined\svgSanitize\Sanitizer as EnshrinedSanitizer;

final readonly class EnshrinedSanitizerAdapter implements Sanitizer
{
    private EnshrinedSanitizer $sanitizer;

    public function __construct(bool $needMinify = true)
    {
        $this->sanitizer = new EnshrinedSanitizer();
        $this->sanitizer->minify($needMinify);
    }

    public function sanitize(string $xml): string
    {
        return $this->sanitizer->sanitize($xml);
    }
}
