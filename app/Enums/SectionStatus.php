<?php

namespace App\Enums;

enum SectionStatus: string
{
    case PLANNED = 'planned';
    case REGISTRATION = 'registration';
    case THESIS_SUBMISSION = 'thesis_submission';
    case THESIS_REVIEW = 'thesis_review';
    case ONGOING = 'ongoing';
    case FINISHED = 'finished';

    public function label(): string
    {
        return match ($this) {
            self::PLANNED => 'Запланирована',
            self::REGISTRATION => 'Регистрация участников',
            self::THESIS_SUBMISSION => 'Отправка тезисов',
            self::THESIS_REVIEW => 'Проверка тезисов',
            self::ONGOING => 'Идёт',
            self::FINISHED => 'Прошла',
        };
    }
}
