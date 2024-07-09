<?php

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Component;

new class extends Component {
    #[Reactive]
    public string $period = '-30 days';

    public array $chartGross = [
        'type' => 'line',
        'options' => [
            'backgroundColor' => '#dfd7f7',
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => [
                    'display' => true
                ],
                'y' => [
                    'display' => true
                ]
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ]
            ],
        ],
        'data' => [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Gross Amount',
                    'data' => [],
                    'tension' => '0.1',
                    'fill' => true,
                ],
            ]
        ]
    ];

    #[Computed]
    public function refreshChartGross(): void
    {
        $invoices = Invoice::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as day, SUM(amount) as gross_amount")
            ->where('created_at', '>=', Carbon::parse($this->period)->startOfDay())
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        Arr::set($this->chartGross, 'data.labels', $invoices->pluck('day')->toArray());
        Arr::set($this->chartGross, 'data.datasets.0.data', $invoices->pluck('gross_amount')->toArray());
    }

    public function with(): array
    {
        $this->refreshChartGross();

        return [
            'chartGross' => $this->chartGross,
        ];
    }


 
};
?>

<div>
    <x-card title="Gross" separator shadow>
        <x-chart wire:model="chartGross" class="h-44" />
    </x-card>
</div>
