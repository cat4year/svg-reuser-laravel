<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\Orchid\Screens\Fields;

use Orchid\Screen\Fields\Select;

/**
 * Class Select.
 *
 * @method Select accesskey($value = true)
 * @method Select autofocus($value = true)
 * @method Select disabled($value = true)
 * @method Select form($value = true)
 * @method Select name(string $value = null)
 * @method Select required(bool $value = true)
 * @method Select size($value = true)
 * @method Select tabindex($value = true)
 * @method Select help(string $value = null)
 * @method Select popover(string $value = null)
 * @method Select options($value = null)
 * @method Select title(string $value = null)
 * @method Select maximumSelectionLength(int $value = 0)
 * @method Select allowAdd($value = true)
 */
final class IconSelect extends Select
{
    /** @var string */
    protected $view = 'svg-reuser::platform.icon-select';
}
