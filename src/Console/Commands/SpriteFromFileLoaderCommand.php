<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\Console\Commands;

use Cat4year\SvgReuser\SvgException;
use Cat4year\SvgReuserLaravel\Models\Icon;
use Cat4year\SvgReuserLaravel\Services\IconLoader;
use Cat4year\SvgReuserLaravel\Services\IconService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

final class SpriteFromFileLoaderCommand extends Command
{
    /** @var string */
    protected $signature = 'sprite:load {--without-update}';

    /** @var string */
    protected $description = 'All svg from sprite in file load to database';

    /**
     * @throws SvgException
     */
    public function handle(IconService $iconService): void
    {
        $withoutUpdate = $this->options()['without-update'];
        $forceSave = !$withoutUpdate;
        $currentIcons = Icon::get()->keyBy('slug');
        $iconService->loadSprite(config('svg-reuser.resource_sprite_path'), false);
        $svgStorage = $iconService->storage();
        $slugs = $svgStorage->getListAllSymbolIds();
        $prefixForRemove = config('svg-reuser.remove_prefix_after_save');
        foreach ($slugs as $slug) {
            if (!empty($prefixForRemove)) {
                $name = str_replace($prefixForRemove, '', $slug);
            }

            $svgFilePath = IconLoader::makeSvgFile($slug, $svgStorage->getCompleteSvg($slug), $forceSave);

            if ($svgFilePath === null) {
                continue;
            }

            if (! $currentIcons->has($slug)) {
                $icon = Icon::createQuietly([
                    'name' => $name,
                    'slug' => $slug,
                    'icon_id' => $this->makeAttachment($slug, $svgFilePath),
                    'sort' => 500,
                ]);
                $currentIcons->put($slug, $icon);
            } elseif ($forceSave) {
                Icon::query()->firstWhere('slug', $slug)?->updateQuietly([
                    'icon_id' => $this->makeAttachment($slug, $svgFilePath),
                ]);
            }
        }

        $iconService->buildSprite();
    }

    protected function makeAttachment(string $slug, string $svgFilePath): int
    {
        return IconLoader::loadAttachment(
            Storage::path($svgFilePath),
            $slug.'.svg',
            'icons'
        )->id;
    }
}
