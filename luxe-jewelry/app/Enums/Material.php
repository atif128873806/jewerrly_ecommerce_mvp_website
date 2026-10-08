<?php

declare(strict_types=1);

namespace App\Enums;

enum Material: string
{
    case Gold = 'gold';
    case GoldPlated = 'gold_plated';
    case Silver = 'silver';
    case Pearl = 'pearl';

    public function label(): string
    {
        return match ($this) {
            self::Gold => '24K Gold',
            self::GoldPlated => '18K Gold Plating',
            self::Silver => '925 Sterling Silver',
            self::Pearl => 'Freshwater Pearl',
        };
    }

    /** Options for filters / selects. */
    public static function options(): array
    {
        return array_map(
            static fn (self $m): array => ['value' => $m->value, 'label' => $m->label()],
            self::cases(),
        );
    }
}
