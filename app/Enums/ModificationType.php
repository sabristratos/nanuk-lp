<?php

declare(strict_types=1);

namespace App\Enums;

enum ModificationType: string
{
    case Text = 'text';
    case Style = 'style';
    case Visibility = 'visibility';
    case Classes = 'classes';
    case Layout = 'layout';

    public function getLabel(): string
    {
        return match ($this) {
            self::Text => 'Change Text / HTML',
            self::Style => 'Change Style',
            self::Visibility => 'Change Visibility',
            self::Classes => 'Add Custom CSS Classes',
            self::Layout => 'Modify Component Layout',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
} 