<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\View\Components;

use Cat4year\SvgReuserLaravel\Services\IconService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
use Cat4year\SvgReuser\SvgException;

final class SvgUse extends Component
{
    public function __construct(
        public ?string $slug,
        private readonly IconService $iconService,
        public ?string $class = '',
    ) {
    }

    public function render(): View|Closure|string|null
    {
        $svgStorage = $this->iconService->storage();
        try {
            $svg = $this->getUseSvg();
        } catch (SvgException $e) {
            if (! $svgStorage->isLoadedSprite()) {
                $this->iconService->loadSprite();
                try {
                    $svg = $this->getUseSvg();
                } catch (SvgException $e) {
                    Log::warning('Second svg load trouble:'.$e->getMessage());
                }
            } elseif (! request()->ajax()) {
                Log::warning('First svg load trouble: '.$e->getMessage());
            }
        }

        return view('svg-reuser::svg-use', ['svg' => $svg ?? null]);
    }

    public function shouldRender(): bool
    {
        return ! empty($this->slug);
    }

    /**
     * @throws SvgException
     */
    private function getUseSvg(): string
    {
        return $this->iconService->storage()->getUseSvg($this->slug ?? '', $this->class ?? '');
    }
}
