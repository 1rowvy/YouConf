<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportExportController extends Controller
{
    protected array $tables = [
        'statuses',
        'locations',
        'users',
        'roles',
        'model_has_roles',
        'sections',
        'section_user',
        'theses',
        'schedules',
        'chats',
        'messages',
        'static_pages',
    ];

    protected array $tableLabels = [
        'statuses' => 'Статусы',
        'locations' => 'Аудитории',
        'users' => 'Пользователи',
        'roles' => 'Роли',
        'model_has_roles' => 'Назначения ролей',
        'sections' => 'Секции',
        'section_user' => 'Секции-Пользователи',
        'theses' => 'Тезисы',
        'schedules' => 'Расписание',
        'chats' => 'Чаты',
        'messages' => 'Сообщения',
        'static_pages' => 'Статические страницы',
    ];

    public function exportAll()
    {
        $dump = [];
        foreach ($this->tables as $table) {
            $dump[$table] = DB::table($table)->get()->toArray();
        }

        return response(
            json_encode($dump, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            200,
            [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename=full_dump.json',
            ]
        );
    }

    public function importAll(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $content = file_get_contents($request->file('file')->getRealPath());
        $dump = json_decode($content, true);

        if (! is_array($dump)) {
            return back()->with('error', 'Невалидный JSON файл');
        }

        try {
            Schema::disableForeignKeyConstraints();

            $importedCount = 0;

            foreach ($this->tables as $table) {
                if (! isset($dump[$table])) {
                    continue;
                }

                $columns = Schema::getColumnListing($table);
                DB::table($table)->delete();

                $rows = $dump[$table];
                foreach (array_chunk($rows, 500) as $chunk) {
                    $filtered = array_map(function ($row) use ($columns) {
                        return array_intersect_key($row, array_flip($columns));
                    }, $chunk);
                    DB::table($table)->insert($filtered);
                }

                $importedCount += count($rows);
            }

            Schema::enableForeignKeyConstraints();

            $imported = array_keys(array_filter($dump, fn ($v, $k) => in_array($k, $this->tables) && ! empty($v), ARRAY_FILTER_USE_BOTH));
            $labels = array_map(fn ($t) => $this->tableLabels[$t] ?? $t, $imported);

            return back()->with('success', 'Импорт завершён ('.$importedCount.' записей): '.implode(', ', $labels));
        } catch (\Throwable $e) {
            Schema::enableForeignKeyConstraints();

            return back()->with('error', "Ошибка импорта: {$e->getMessage()}");
        }
    }
}
