<?php

use App\Models\Payment;
use App\Models\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Traits\ClearsProperties;
use App\Traits\ResetsPaginationWhenPropsChanges;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

new class extends Component {
    use WithPagination, ResetsPaginationWhenPropsChanges, ClearsProperties;

    #[Url]
    public string $invoiceId = '';

    #[Url]
    public int $status_id = 0;

    #[Url]
    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public bool $showFilters = false;

    public function filterCount(): int
    {
        return ($this->status_id ? 1 : 0) + (strlen($this->invoiceId) ? 1 : 0);
    }

    public function payments(): LengthAwarePaginator
    {
        return Payment::query()
            ->with(['status', 'invoice', 'student', 'billMethodQuantity'])
            ->when($this->invoiceId, fn(Builder $q) => $q->where('invoice_id', 'like', "%{$this->invoiceId}%"))
            ->when($this->status_id, fn(Builder $q) => $q->where('status_id', $this->status_id))
            ->orderBy(...array_values($this->sortBy))
            ->paginate(10);
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => 'No', 'class' => '', 'sortable' => true],
            ['key' => 'invoice', 'label' => 'invoiceId', 'sortable' => true],
            ['key' => 'amount', 'label' => 'Amount', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortBy' => 'status_id', 'class' => 'hidden lg:table-cell'],
            ['key' => 'action', 'label' => 'Action', 'sortable' => false],
        ];
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'payments' => $this->payments(),
            'statuses' => Status::all(),
            'filterCount' => $this->filterCount()
        ];
    }
};
?>

<div>
    <x-header title="Payments" separator progress-indicator>
        {{-- SEARCH --}}
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search by Invoice ID..." wire:model.debounce.300ms="invoiceId" icon="o-magnifying-glass" clearable />
        </x-slot:middle>

        {{-- ACTIONS --}}
        <x-slot:actions>
            <x-button
                label="Filters"
                icon="o-funnel"
                :badge="$filterCount"
                badge-classes="font-mono"
                @click="$wire.showFilters = true"
                class="bg-base-300"
                responsive />

            <x-button label="Add Payment" icon="o-plus" link="/payments/create" class="btn-primary" responsive />
        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table :headers="$headers" :rows="$payments" link="/payments/{id}/show" :sort-by="$sortBy" with-pagination>
            @scope('cell_invoice', $payment)
            <x-badge :value="$payment->invoice->invoiceId" class="font-bold"  />
        @endscope
            @scope('cell_status', $payment)
                <x-badge :value="$payment->status->name" :class="$payment->status->color" />
            @endscope
            @scope('cell_action', $payment)
                <x-button label="Edit" link="/payments/{{ $payment->id }}/edit" icon="o-pencil" class="btn-primary" responsive />
            @endscope
        </x-table>
    </x-card>

    <x-drawer wire:model="showFilters" title="Filters" class="lg:w-1/3" right separator with-close-button>
        <div class="grid gap-5" @keydown.enter="$wire.showFilters = false">
            <x-input label="Invoice ID ..." wire:model.debounce="invoiceId" icon="o-user" inline />
            <x-select label="Status" :options="$statuses" wire:model="status_id" icon="o-map-pin" placeholder="All"
                placeholder-value="0" inline />
        </div>

        {{-- ACTIONS --}}
        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.showFilters = false" />
        </x-slot:actions>
    </x-drawer>
</div>
