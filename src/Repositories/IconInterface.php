<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\Repositories;

use Cat4year\SvgReuserLaravel\Models\Icon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IconInterface
{
    public function getModel(): Icon;

    /**
     * @return Collection<int, Icon>
     */
    public function getAll(): Collection;

    /**
     * @return LengthAwarePaginator<Icon>
     */
    public function platformPaginate(): LengthAwarePaginator;

    /**
     * @param array<string, mixed> $data
     */
    public function updateFromPlatform(Icon $model, array $data): Icon;
}
