<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
use Cat4year\SvgReuser\SvgException;
use Cat4year\SvgReuser\SvgStorage;

final class SvgSprite extends Component
{
    public function __construct(public bool $onlyUsed = false)
    {
    }

    public function render(): View|Closure|string|null
    {
        /** @var SvgStorage $svgStorage */
        $svgStorage = app('svgStorage');

        try {
            $svg = $svgStorage->getSprite($this->onlyUsed, config('svg-reuser.default_class_svg_sprite'));
        } catch (SvgException $e) {
            Log::warning($e->getMessage());
        }

        return isset($svg) ? view('svg-reuser::svg-sprite', ['svg' => $svg]) : null;
    }
}
