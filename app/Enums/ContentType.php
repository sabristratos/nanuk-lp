<?php

declare(strict_types=1);

namespace App\Enums;

enum ContentType: string
{
    case Text = 'text';
    case Html = 'html';
    case Markdown = 'markdown';

    public function getLabel(): string
    {
        return match ($this) {
            self::Text => __('Plain Text'),
            self::Html => __('HTML'),
            self::Markdown => __('Markdown'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
} 