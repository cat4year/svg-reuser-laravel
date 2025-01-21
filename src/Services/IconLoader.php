<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Orchid\Attachment\File;
use Orchid\Attachment\Models\Attachment;

final class IconLoader
{
    public static function loadAttachment(string $path, string $originalName, ?string $group = null): Attachment
    {
        $file = new UploadedFile($path, $originalName);

        /** @var Attachment $attachment */
        $attachment = (new File($file, group: $group))->load();

        return $attachment;
    }

    public static function makeSvgFile(string $slug, string $content, bool $forceSave = false): ?string
    {
        $pathFileName = sprintf('%s/%s', config('svg-reuser.private_symbols_directory'), $slug.'.svg');

        if (! $forceSave && Storage::fileExists($pathFileName)) {
            return $pathFileName;
        }

        $result = Storage::put($pathFileName, $content);

        return $result === false ? null : $pathFileName;
    }
}
