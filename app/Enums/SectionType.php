<?php

declare(strict_types=1);

namespace App\Enums;

enum SectionType: string
{
    case ScrollableProduct = 'scrollable_product';
    case SpotlightDeals = 'spotlight_deals';
    case LightingDeals = 'lighting_deals';
    case Search = 'search';
    case Banner = 'banner';
}
