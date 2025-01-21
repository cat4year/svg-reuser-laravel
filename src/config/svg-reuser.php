<?php

declare(strict_types=1);

return [
    'resource_sprite_path' => env('SVG_REUSER_RESOURCE_SPRITE_PATH', resource_path('sprite.svg')),
    'built_sprite_path' => env('SVG_REUSER_BUILT_SPRITE_PATH', storage_path('app/public/icons/sprite.svg')),
    'remove_prefix_after_save' => env('SVG_REUSER_REMOVE_PREFIX_AFTER_SAVE', 'icon-'),
    'private_symbols_directory' => env('SVG_REUSER_PRIVATE_SYMBOLS_DIRECTORY', storage_path('app/private/icons')),
    //false|0|null|'' - for remove menu item
    'menu_sort' => env('SVG_REUSER_MENU_SORT', 100),
    'default_class_svg_sprite' => env('SVG_REUSER_DEFAULT_CLASS_SVG_SPRITE', 'd-none'),
];
