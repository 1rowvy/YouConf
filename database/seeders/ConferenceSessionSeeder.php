<?php

namespace Database\Seeders;

use App\Models\Thesis;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Validation\ValidationException;

class ConferenceSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->isProduction()) {
            return;
        }

        $theses = Thesis::with('section')->where('status_id', 2)->get();

        $durations = [15, 30, 45, 60];

        // Проходим по каждой заявке
        foreach ($theses as $thesis) {

            $section = $thesis->section;

            $startDate = Carbon::parse($section->start_date);
            $endDate = Carbon::parse($section->end_date);
            $daysDiff = $startDate->diffInDays($endDate);

            // Генерация времени начала и продолжительности
            $start_time = null;
            $duration = null;
            $end_time = null;
            $date = null;
            $attempts = 0; // Счётчик попыток
            $maxAttempts = 100; // Максимальное количество попыток

            do {
                $date = $startDate->copy()->addDays(rand(0, $daysDiff));
                // Генерируем случайное время начала с 8:00 до 20:00 с шагом 15 минут
                // $start_time = Carbon::createFromTime(rand(8, 20), rand(0, 3) * 15);
                $start_time = Carbon::createFromTime(rand(8, 17), rand(0, 3) * 15);

                // Выбираем случайную продолжительность
                $duration = $durations[array_rand($durations)];

                // Вычисляем время окончания
                $end_time = $start_time->copy()->addMinutes($duration);

                // Проверяем, есть ли пересекающиеся расписания для этой секции
                $conflictingSchedules = Schedule::whereHas('thesis', function ($query) use ($thesis) {
                    $query->where('section_id', $thesis->section_id); // Проверяем по section_id
                })
                    ->where(function ($query) use ($start_time, $end_time) {
                        $query->where(function ($q) use ($start_time, $end_time) {
                            $q->where('start_time', '<', $end_time->format('H:i'))
                                ->whereRaw('ADDTIME(start_time, SEC_TO_TIME(duration * 60)) > ?', [$start_time->format('H:i')]);
                        });
                    })
                    ->exists();

                $attempts++;
            } while ($conflictingSchedules && $attempts < $maxAttempts);

            // Если не удалось найти непересекающееся расписание, пропускаем заявку
            if ($attempts >= $maxAttempts) {
                echo "Не удалось найти время для заявки ID {$thesis->id}.\n";
                continue;
            }

            // Пытаемся создать запись в расписании
            try {
                Schedule::create([
                    'thesis_id' => $thesis->id,
                    'start_time' => $start_time->format('H:i'),
                    'date'        => $date->format('Y-m-d'),
                    'duration' => $duration,
                    'end_time' => $end_time->format('H:i'),
                ]);
            } catch (ValidationException $e) {
                // Логируем ошибку и продолжаем выполнение
                echo "Ошибка при создании расписания для заявки ID {$thesis->id}: " . $e->getMessage() . "\n";
                continue;
            }
        }
    }
}
