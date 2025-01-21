<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\Orchid\Screens;

use Cat4year\SvgReuserLaravel\Models\Icon;
use Cat4year\SvgReuserLaravel\Repositories\IconInterface;
use Cat4year\SvgReuserLaravel\View\Components\SvgSprite;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Cell;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

final class IconListScreen extends Screen
{
    public function __construct(
        private readonly IconInterface $repository,
    ) {
    }

    public function name(): ?string
    {
        return 'Справочник иконок';
    }

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array<string, mixed>
     */
    public function query(): iterable
    {
        return [
            'items' => $this->repository->platformPaginate(),
        ];
    }

    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Создать')
                ->modal('modal')
                ->type(Color::PRIMARY)
                ->modalTitle('Создать')
                ->method('update')
                ->icon('bs.window'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::component(SvgSprite::class),
            Layout::modal('modal', [
                Layout::rows([
                    Input::make('item.name')
                        ->title('Название')
                        ->required(),

                    Input::make('item.slug')
                        ->title('Символьный код')
                        ->help('Будет использоваться как id для symbol в svg. Будет заполнено автоматически из поля "Название"'),

                    Input::make('item.sort')
                        ->title('Сортировка')
                        ->value(500),

                    Upload::make('item.icon_id')
                        ->maxFiles(1)
                        ->acceptedFiles('.svg')
                        ->title('Иконка'),
                ]),
            ])
                ->size(Modal::SIZE_XL)
                ->applyButton('Сохранить')
                ->async('asyncGetData'),
            new class extends Table
            {
                protected $target = 'items';

                /**
                 * @return array<Cell>
                 */
                protected function columns(): iterable
                {
                    return [
                        TD::make('', 'Редактировать')
                            ->width(100)
                            ->render(static fn (Icon $item) => ModalToggle::make('Редактировать')
                                ->modal('modal')
                                ->type(Color::SUCCESS)
                                ->modalTitle('Редактировать '.$item->name)
                                ->method('update')
                                ->asyncParameters([
                                    'item' => $item->id,
                                ])),
                        TD::make('name', 'Название')
                            ->sort()
                            ->filter(),
                        TD::make('icon', 'Иконка')
                            ->alignCenter()
                            ->render(static fn (Icon $item) => $item->slug
                                ? '<svg class="svg-symbol-bg"><use href="#'
                                .$item->slug.'" xlink:href="'.$item->slug
                                .'" x="0" y="0"/></svg>'
                                : ''
                            ),
                        TD::make('sort', 'Сортировка')->sort(),
                        TD::make('created_at', 'Дата создания')
                            ->sort()
                            ->defaultHidden(),
                        TD::make('', '')
                            ->width(100)
                            ->render(static fn (Icon $item) => Button::make(__('Delete'))
                                ->icon('bs.trash3')
                                ->type(Color::DANGER)
                                ->confirm(__('Вы уверены что хотите удалить?'))
                                ->method('remove', [
                                    'id' => $item->id,
                                ])),
                    ];
                }
            },
        ];
    }

    /**
     * @return Icon[]
     */
    public function asyncGetData(Icon $item): array
    {
        return [
            'item' => $item,
        ];
    }

    public function update(Request $request, Icon $item): void
    {
        $requestData = $request->except('_token');
        $itemData = $requestData['item'] ?? [];

        $itemData['icon_id'] = reset($itemData['icon_id']);

        $this->repository->updateFromPlatform($item, $itemData);
        Toast::info(__('Сохранение успешно'));
    }

    public function remove(Request $request): void
    {
        $this->repository->getModel()::where('id', $request->get('id'))->delete();

        Toast::info(__('Удалено'));
    }
}
