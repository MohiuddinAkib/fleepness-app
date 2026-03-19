<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SectionType: string implements HasColor, HasIcon, HasLabel
{
    case ScrollableProduct = 'scrollable_product';
    case SpotlightDeals = 'spotlight_deals';
    case LightingDeals = 'lighting_deals';
    case Search = 'search';
    case Banner = 'banner';

    public function getLabel(): string
    {
        return match ($this) {
            self::ScrollableProduct => 'Scrollable Product',
            self::SpotlightDeals => 'Spotlight Deals',
            self::LightingDeals => 'Lightning Deals',
            self::Search => 'Search',
            self::Banner => 'Banner',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ScrollableProduct => 'info',
            self::SpotlightDeals => 'warning',
            self::LightingDeals => 'danger',
            self::Search => 'gray',
            self::Banner => 'primary',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::ScrollableProduct => 'heroicon-o-view-columns',
            self::SpotlightDeals => 'heroicon-o-star',
            self::LightingDeals => 'heroicon-o-bolt',
            self::Search => 'heroicon-o-magnifying-glass',
            self::Banner => 'heroicon-o-photo',
        };
    }

    public function showsProducts(): bool
    {
        return match ($this) {
            self::ScrollableProduct, self::SpotlightDeals, self::LightingDeals, self::Search => true,
            self::Banner => false,
        };
    }
}
