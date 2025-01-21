<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\Traits;

use Illuminate\Support\Str;
use InvalidArgumentException;

trait Slugable
{
    protected string $generateSlugFrom = 'name';

    protected static function bootSlugable(): void
    {
        static::creating(static function ($model): void {
            $model->generateSlugOnCreate($model->slug);
        });
    }

    protected function generateSlugOnCreate(?string $inputSlug = null): void
    {
        if (empty($inputSlug)) {
            if (! $this->hasAttribute($this->generateSlugFrom)) {
                throw new InvalidArgumentException("Slug attribute can't set.");
            }

            $inputSlug = $this->getAttribute($this->generateSlugFrom);
        }

        $slug = Str::slug($inputSlug);

        $latest = static::whereRaw("slug ~ '^{$slug}(-[0-9]+)?$'")
            ->latest('id')
            ->value('slug');

        if ($latest) {
            $pieces = explode('-', $latest);
            $number = (int) end($pieces);
            $slug .= '-'.($number + 1);
        }

        $this->slug = $slug;
    }
}
