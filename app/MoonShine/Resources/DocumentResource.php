<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Document;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @extends ModelResource<Document>
 */
class DocumentResource extends ModelResource
{
    protected string $model = Document::class;

    protected string $title = 'Регламентирующие документы';

    protected string $sortColumn = 'sort_order';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'title'),
            Switcher::make('Опубликован', 'is_published'),
            Number::make('Порядок', 'sort_order')->sortable(),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Название', 'title')
                    ->required(),
                File::make('Файл', 'file_path')
                    ->disk('public')
                    ->dir('documents')
                    ->allowedExtensions(['pdf', 'doc', 'docx'])
                    ->removable(),
                Number::make('Порядок сортировки', 'sort_order')
                    ->default(0),
                Switcher::make('Опубликован', 'is_published')
                    ->default(true),
            ]),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Название', 'title'),
            File::make('Файл', 'file_path')
                ->disk('public')
                ->dir('documents'),
            Number::make('Порядок', 'sort_order'),
            Switcher::make('Опубликован', 'is_published'),
        ];
    }

    /**
     * @param Document $item
     *
     * @return array<string, string[]|string>
     */
    protected function rules(mixed $item): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'file_path'    => [$item->exists ? 'nullable' : 'required'],
            'sort_order'   => ['integer', 'min:0'],
            'is_published' => ['boolean'],
        ];
    }
}
