<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\Observers;

use Cat4year\SvgReuserLaravel\Models\Icon;
use Cat4year\SvgReuserLaravel\Services\IconService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

final readonly class IconObserver implements ShouldHandleEventsAfterCommit
{
    public function __construct(private IconService $iconService)
    {
    }

    public function created(Icon $icon): void
    {
        $this->iconService->buildSprite();
    }

    public function updated(Icon $icon): void
    {
        $this->iconService->buildSprite();
    }

    public function deleted(Icon $icon): void
    {
        $this->iconService->buildSprite();
    }

    public function restored(Icon $icon): void
    {
        $this->iconService->buildSprite();
    }

    public function forceDeleted(Icon $icon): void
    {
        $this->iconService->buildSprite();
    }
}
