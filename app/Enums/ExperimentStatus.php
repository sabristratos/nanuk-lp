<?php

declare(strict_types=1);

namespace App\Enums;

enum ExperimentStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Paused = 'paused';
    case Completed = 'completed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => __('Draft'),
            self::Active => __('Active'),
            self::Paused => __('Paused'),
            self::Completed => __('Completed'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'secondary',
            self::Active => 'success',
            self::Paused => 'warning',
            self::Completed => 'info',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
} 