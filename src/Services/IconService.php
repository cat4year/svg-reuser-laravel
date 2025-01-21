<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\Services;

use Cat4year\SvgReuserLaravel\Models\Icon;
use Cat4year\SvgReuserLaravel\Repositories\IconInterface;
use DOMException;
use ErrorException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Cat4year\SvgReuser\SvgException;
use Cat4year\SvgReuser\SvgSpriteBuilder;
use Cat4year\SvgReuser\SvgStorage;

final readonly class IconService
{
    public function __construct(
        private IconInterface $iconRepository,
        private SvgSpriteBuilder $svgSpriteBuilder,
    ) {
    }

    public function loadSprite(string $path = '', bool $needBuildOnFail = true): void
    {
        if ($path === '') {
            $path = $this->getSpritePath();
        }

        try {
            $this->storage()->loadSprite($path);
        } catch (ErrorException $e) {
            if ($needBuildOnFail) {
                $this->buildSprite($path);
            } else {
                Log::warning($e->getMessage());
            }
        } catch (SvgException $e) {
            Log::warning($e->getMessage());
        }
    }

    public function buildSprite(string $path = '', bool $needLoadAfterBuild = true): void
    {
        if ($path === '') {
            $path = $this->getSpritePath();
        }

        $icons = $this->iconRepository->getAll();

        $filePaths = $icons->keyBy('slug')
            ->filter(static fn (Icon $icon) => $icon->icon !== null)
            /** @phpstan-ignore-next-line */
            ->map(static fn (Icon $icon) => Storage::disk('public')->path($icon->icon->physicalPath()))
            ->toArray();

        $spritePath = $this->getSpritePath();

        try {
            $this->svgSpriteBuilder->buildSpriteFromPaths($filePaths, $spritePath);
            if ($needLoadAfterBuild) {
                $this->storage()->loadSprite($path);
            }
        } catch (SvgException|ErrorException|DOMException $e) {
            Log::error($e->getMessage());
        }
    }

    private function getSpritePath(): string
    {
        return config('svg-reuser.built_sprite_path');
    }

    public function storage(): SvgStorage
    {
        return app('svgStorage');
    }
}
