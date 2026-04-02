<?php

declare(strict_types=1);

namespace App\MoonShine\Components;

use Closure;
use MoonShine\UI\Components\MoonShineComponent;
use MoonShine\UI\Fields\Textarea;

/**
 * @method static static make()
 */
final class QuillEditor extends Textarea
{
    protected string $view = 'admin.components.quill-editor';

    public function save(mixed $value): mixed
    {
        return $value;
    }

    public function __construct(
        string $label,
        ?string $column = null,
        ?Closure $formatted = null
    ) {
        parent::__construct($label, $column, $formatted);
    }

    protected function viewData(): array
    {
        return [];
    }
}
