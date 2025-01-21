<?php

namespace Cat4year\SvgReuserLaravel\Providers;

use Cat4year\SvgReuser\Sanitizer\Sanitizer;
use Cat4year\SvgReuser\SvgStorage;
use Cat4year\SvgReuserLaravel\Console\Commands\SpriteFromFileLoaderCommand;
use Cat4year\SvgReuserLaravel\Orchid\Screens\IconListScreen;
use Cat4year\SvgReuserLaravel\Repositories\IconInterface;
use Cat4year\SvgReuserLaravel\Repositories\IconRepository;
use Cat4year\SvgReuserLaravel\Services\EnshrinedSanitizerAdapter;
use Cat4year\SvgReuserLaravel\View\Components\SvgSprite;
use Cat4year\SvgReuserLaravel\View\Components\SvgUse;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Orchid\Platform\Dashboard;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

class ServiceProvider extends OrchidServiceProvider
{
    /** @var array<class-string, class-string> */
    public array $bindings = [
        IconInterface::class => IconRepository::class,
        Sanitizer::class => EnshrinedSanitizerAdapter::class
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/svg-reuser.php', 'svg-reuser'
        );
        $this->app->singleton('svgStorage', SvgStorage::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        $this->publishes([
            __DIR__.'/../config/svg-reuser.php' => config_path('svg-reuser.php'),
        ], ['config', 'recommended']);

        $oldBasePath = base_path();
        app()->setBasePath(config('svg-reuser.publish_base_path'));

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], ['migrations', 'minimal', 'recommended', 'orchid']);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'svg-reuser');

        $this->publishes([
            __DIR__.'/../resources/views/platform' => resource_path('views/platform'),
            __DIR__.'/../Orchid/Screens/Fields' => app_path('Orchid/Screens/Fields'),
        ], ['views', 'recommended', 'orchid']);
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/components'),
            __DIR__.'/../View/Components' => app_path('View/Components'),
        ], ['views', 'recommended']);

        Blade::component('svg-sprite', SvgSprite::class);
        Blade::component('svg-use', SvgUse::class);

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/svg-reuser'),
        ], ['public', 'minimal', 'recommended']);

        $this->publishes([
            __DIR__.'/../Console/Commands' => app_path('Console/Commands'),
        ], ['commands', 'recommended', 'orchid']);

        $this->publishes([
            __DIR__.'/../Middleware' => app_path('Middleware'),
        ], ['middleware', 'recommended']);

        $this->publishes([
            __DIR__.'/../Models' => app_path('Models'),
        ], ['models', 'recommended', 'orchid']);

        $this->publishes([
            __DIR__.'/../Observers' => app_path('Observers'),
        ], ['observers', 'recommended']);

        $this->publishes([
            __DIR__.'/../Orchid/Screens/IconListScreen.php' => app_path('Orchid/Screens/IconListScreen.php'),
        ], ['screens', 'recommended', 'orchid']);

        $this->publishes([
            __DIR__.'/../Providers' => app_path('Providers'),
        ], ['providers', 'recommended']);

        $this->publishes([
            __DIR__.'/../Repositories' => app_path('Repositories'),
        ], ['repositories', 'recommended']);

        $this->publishes([
            __DIR__.'/../Services' => app_path('Services'),
        ], ['services', 'recommended']);

        AboutCommand::add(
            'Svg Reuser',
            static fn () => [
                'version' => '1.0.0',
                'resource_sprite_path' => config('svg-reuser.resource_sprite_path'),
                'built_sprite_path' => config('svg-reuser.built_sprite_path'),
                'private_symbols_directory' => config('svg-reuser.private_symbols_directory'),
                'remove_prefix_after_save' => config('svg-reuser.remove_prefix_after_save'),
            ]
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                SpriteFromFileLoaderCommand::class,
            ]);
        }

        app()->setBasePath($oldBasePath);
    }

    public function routes(Router $router): void
    {
        $router->screen('icons', IconListScreen::class)->name('platform.svg-reuser.icons');
    }

    public function menu(): array
    {
        $menuSort = config('svg-reuser.menu_sort');
        if (empty($menuSort)) {
            return [];
        }

        return [
            Menu::make(__('Иконки'))
                ->icon('bs.scissors')
                ->route('platform.svg-reuser.icons')
                ->sort($menuSort),
        ];
    }

    public function stylesheets(): array
    {
        return [
            '/vendor/svg-reuser/platform.css'
        ];
    }

    public function scripts(): array
    {
        return [
            '/vendor/svg-reuser/platform.js'
        ];
    }
}
