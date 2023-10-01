<?php

namespace App\Livewire;

use App\Models\Competitor;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ProductsCompetidorCountChart extends ChartWidget
{
    protected static ?string $heading = 'Productos competidor';

    protected function getData(): array
    {
        $data = Trend::model(Competitor::class)
            ->between(
                start: now()->subDays(30),
                end: now()->endOfDay(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Productos competidores',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
