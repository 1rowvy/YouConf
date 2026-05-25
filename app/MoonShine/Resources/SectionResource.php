<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Enums\SectionStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Components\ActionButton;

use MoonShine\Support\ListOf;
use MoonShine\UI\Components\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Enum;
use MoonShine\UI\Fields\Number;

/**
 * @extends ModelResource<Section>
 */
class SectionResource extends ModelResource
{
    protected string $model = Section::class;

    protected string $title = 'Sections';

    protected bool $createInModal = true;

    protected bool $editInModal = true;

    protected function indexButtons(): ListOf
    {
        return parent::indexButtons()
            ->except(fn(ActionButton $btn) => $btn->getName() === 'resource-detail-button');
    }

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),

            Enum::make('Статус', 'status')->attach(SectionStatus::class)->sortable(),

            Text::make('Название', 'name')->required(),
            Text::make('Описание', 'description')->required(),
            Text::make('Полное описание', 'full_description')->required(),

            BelongsToMany::make('Аудитории', 'locations', resource: LocationResource::class)->onlyCount(),
            BelongsToMany::make('Участиники', 'users', resource: UserResource::class)->onlyCount(),
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
                Text::make('Название', 'name'),

                Enum::make('Статус', 'status')->attach(SectionStatus::class),

                Text::make('Описание', 'description'),
                Text::make('Полное описание', 'full_description'),
                Text::make('Председатели', 'chairs'),

                BelongsToMany::make('Аудитории', 'locations', resource: LocationResource::class),
            ]),

            Box::make('Реценизрование', [
                Number::make('Лимит проверок', 'revision_limit'),
            ]),

            Box::make('Даты проведения', [
                Date::make('Дата начала', 'start_date')->required(),
                Date::make('Дата окончания', 'end_date')->required(),
            ]),

            Box::make('Работа секции', [
                Text::make('Начало работы секции', 'start_time')->setAttribute('type', 'time')->required(),
                Text::make('Конец работы секции', 'end_time')->setAttribute('type', 'time')->required(),
                Number::make('Время защиты (в минутах)', 'presentation_duration')->required()
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
            Text::make('Название', 'name'),
            Enum::make('Статус', 'status')->attach(SectionStatus::class),
        ];
    }

    /**
     * @param Section $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
