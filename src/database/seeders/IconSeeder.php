<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

final class IconSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Artisan::call('sprite:load');
    }
}
