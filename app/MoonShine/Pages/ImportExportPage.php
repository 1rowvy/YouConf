<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\Laravel\Pages\Page;
use MoonShine\UI\Components\FlexibleRender;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;

class ImportExportPage extends Page
{
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle(),
        ];
    }

    public function getTitle(): string
    {
        return 'Импорт / Экспорт';
    }

    protected function components(): iterable
    {
        return [
            FlexibleRender::make($this->buildFlashHtml()),
            Grid::make([
                Column::make([
                    Box::make('Экспорт данных', [
                        FlexibleRender::make($this->buildExportSection()),
                    ]),
                ])->columnSpan(6),
                Column::make([
                    Box::make('Импорт данных', [
                        FlexibleRender::make($this->buildImportSection()),
                    ]),
                ])->columnSpan(6),
            ]),
        ];
    }

    protected function buildFlashHtml(): string
    {
        $html = '';
        if (session('success')) {
            $html .= '<div style="padding:12px 16px;margin-bottom:16px;border-radius:8px;background:#d1fae5;color:#065f46;border:1px solid #6ee7b7">'.e(session('success')).'</div>';
        }
        if (session('error')) {
            $html .= '<div style="padding:12px 16px;margin-bottom:16px;border-radius:8px;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5">'.e(session('error')).'</div>';
        }

        return $html;
    }

    protected function buildExportSection(): string
    {
        $html = '<p style="margin-bottom:12px;color:#6b7280">Скачать полный дамп всех таблиц базы данных в формате JSON.</p>';
        $html .= '<a href="'.route('admin.export.all').'" style="display:inline-block;padding:10px 20px;background:#3b82f6;color:#fff;border-radius:6px;text-decoration:none;font-weight:600">Скачать дамп (JSON)</a>';

        return $html;
    }

    protected function buildImportSection(): string
    {
        $html = '<form method="POST" action="'.route('admin.import.all').'" enctype="multipart/form-data">';
        $html .= csrf_field();

        $html .= '<p style="margin-bottom:12px;color:#6b7280">Загрузить JSON-файл полного дампа для восстановления данных.</p>';

        $html .= '<div style="margin-bottom:12px">';
        $html .= '<input type="file" name="file" accept=".json" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px">';
        $html .= '</div>';

        $html .= '<div style="padding:8px 12px;margin-bottom:12px;border-radius:6px;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;font-size:0.9em">';
        $html .= 'Все текущие данные будут заменены данными из файла!';
        $html .= '</div>';

        $html .= '<button type="submit" style="padding:10px 20px;background:#ef4444;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:600">Импортировать</button>';
        $html .= '</form>';

        return $html;
    }
}
