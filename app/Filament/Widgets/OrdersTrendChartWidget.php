<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Date;

class OrdersTrendChartWidget extends ChartWidget
{
    protected ?string $heading = 'Orders & Revenue (Last 7 Days)';

    protected ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $startDate = Date::today()->subDays(6);
        $orders = Order::query()
            ->whereDate('created_at', '>=', $startDate)
            ->get()
            ->groupBy(fn (Order $order): string => $order->created_at->toDateString());

        $labels = collect(range(0, 6))
            ->map(fn (int $offset): string => $startDate->copy()->addDays($offset)->format('M j'))
            ->all();

        $orderCounts = collect(range(0, 6))
            ->map(function (int $offset) use ($orders, $startDate): int {
                $date = $startDate->copy()->addDays($offset)->toDateString();

                return $orders->get($date)?->count() ?? 0;
            })
            ->all();

        $revenues = collect(range(0, 6))
            ->map(function (int $offset) use ($orders, $startDate): float {
                $date = $startDate->copy()->addDays($offset)->toDateString();

                return (float) ($orders->get($date)?->sum('grand_total') ?? 0);
            })
            ->all();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $orderCounts,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.12)',
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Revenue',
                    'data' => $revenues,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.12)',
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
