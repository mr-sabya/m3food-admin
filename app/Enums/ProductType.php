<?php

namespace App\Enums;

enum ProductType: string
{
    case Normal = 'normal';
    case Variable = 'variable';
    case Affiliate = 'affiliate';
    case Digital = 'digital';

    public function label(): string
    {
        return match ($this) {
            self::Normal => 'Normal Product',
            self::Variable => 'Variable Product',
            self::Affiliate => 'Affiliate Product',
            self::Digital => 'Digital Product',
        };
    }

    public function isNormal(): bool
    {
        return $this === self::Normal;
    }
    public function isVariable(): bool
    {
        return $this === self::Variable;
    }
    public function isAffiliate(): bool
    {
        return $this === self::Affiliate;
    }
    public function isDigital(): bool
    {
        return $this === self::Digital;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
