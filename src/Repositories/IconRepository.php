<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\Repositories;

use Cat4year\SvgReuserLaravel\Models\Icon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class IconRepository implements IconInterface
{
    public function __construct(protected Icon $model)
    {
    }

    public function getModel(): Icon
    {
        return $this->model;
    }

    public function getAll(): Collection
    {
        return $this->model::query()->with('icon')->get();
    }

    public function platformPaginate(): LengthAwarePaginator
    {
        return $this->model::filters()
            ->orderBy('sort')
            ->paginate();
    }

    public function updateFromPlatform(Icon $model, array $data): Icon
    {
        $model->fill($data)->save();

        return $model;
    }
}
