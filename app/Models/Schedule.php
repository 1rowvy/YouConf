<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'section_id',
        'date',
        'start_time',
        'duration',
        'end_time',
        'event_type',
        'title',
        'description'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
    ];

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = is_string($value) ? $value : $value->format('H:i:s');
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = is_string($value) ? $value : $value->format('H:i:s');
    }

    public function isThesis(): bool
    {
        return $this->event_type === 'thesis';
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }


    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($schedule) {
            if ($schedule->thesis_id) {
                $thesis = Thesis::find($schedule->thesis_id);
                if ($thesis) {
                    $schedule->section_id = $thesis->section_id;
                }
            }
        });
        static::saving(function ($schedule) {
            $startTime = Carbon::parse($schedule->start_time);
            $endTime = $startTime->copy()->addMinutes((int)$schedule->duration);

            $conflictingSchedules = Schedule::where('section_id', $schedule->section_id)
                ->where('date', $schedule->date)
                ->where(function ($query) use ($schedule) {
                    $query->where(function ($q) use ($schedule) {
                        $q->where('start_time', '<', $schedule->end_time)
                            ->where('end_time', '>', $schedule->start_time);
                    });
                })
                ->exists();

            if ($conflictingSchedules) {
                throw ValidationException::withMessages([
                    'start_time' => 'Расписание пересекается с другим мероприятием в этой секции.',
                ]);
            }
        });
    }
}
