<?php

use App\Models\Student;
use App\Models\Invoice;
use App\Models\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    #[Url]
    public string $invoiceId = '';

    #[Url]
    public int $status_id = 0;

    #[Url]
    public array $sortBy = ['column' => 'invoiceId', 'direction' => 'asc'];

    public bool $showFilters = false;

    public function filterCount(): int
    {
        return ($this->status_id ? 1 : 0) + ($this->invoiceId ? 1 : 0);
    }

    public function invoices(): LengthAwarePaginator
    {
        return Invoice::query()
            ->with(['status'])
            ->when($this->invoiceId, fn(Builder $q) => $q->where('invoiceId', 'like', "%$this->invoiceId%"))
            ->when($this->status_id, fn(Builder $q) => $q->where('status_id', $this->status_id))
            ->orderBy(...array_values($this->sortBy))
            ->paginate(100);
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => 'No', 'class' => '', 'sortable' => true],
            ['key' => 'invoiceId', 'label' => 'invoiceId'],
            ['key' => 'status', 'label' => 'Status', 'sortBy' => 'status_name', 'class' => 'hidden lg:table-cell'],
            ['key' => 'action', 'label' => 'action', 'sortBy' => '', 'class' => 'hidden lg:table-cell'],
        ];
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'invoices' => $this->invoices(),
            'statuses' => Status::all(),
            'filterCount' => $this->filterCount()
        ];
    }
};
?>
<div>
    <x-header title="Invoices" separator progress-indicator>
            <x-input label="invoiceId ..." wire:model.live.debounce="invoiceId" icon="m-user" inline />
            <x-select label="Status" :options="$statuses" wire:model.live="status_id" icon="m-paper-airplane" placeholder="All"
                placeholder-value="0" inline />

        {{-- ACTIONS --}}
    </x-header>
    <x-card>
        <div class="flex">
        <x-input label="invoiceId ..." wire:model.live.debounce="invoiceId" icon="m-user" inline />
        <x-select label="Status" :options="$statuses" wire:model.live="status_id" icon="m-paper-airplane" placeholder="All"
            placeholder-value="0" inline />
        </div>

        @if($invoices->count() > 0)
        <div class="p-6">
            <h1 class="text-lg font-semibold">Invoices</h1>
            <table class="min-w-full mt-4 divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Student ID</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Fee Amount</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount Paid</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Restant</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($invoices as $invoice)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-green-500 underline whitespace-nowrap">{{ $invoice->invoiceId }}</td>
                        <td class="px-6 py-4 text-sm text-black">{{ $invoice->studentId }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ number_format($invoice->amount) }} DJF</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ number_format($invoice->amount_paid) }} DJF</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ number_format($invoice->remaining) }} DJF</td>
                        <td class="px-6 py-4 "> <span class="{{ $invoice->status->color }} px-2 py-1 rounded-full">
                            {{ $invoice->status->name }}
                        </span></td>
                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">     <x-button label="Show" link="{{ route('invoices.edit', ['invoice' => $invoice->id]) }}" icon="o-eye" class="btn-primary" responsive />

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination links -->
            <div class="mt-4">
                {{ $invoices->links() }}
            </div>
        </div>
        @else
        <div class="flex items-center justify-center gap-10 mx-auto">
            <div>
                <img src="/images/empty_student.png" width="300" />
            </div>
            <div class="text-lg font-medium">
                Sorry {{ Auth::user()->name }}, No invoice.
            </div>
        </div>
        @endif
    </x-card>

    <x-drawer wire:model="showFilters" title="Filters" class="lg:w-1/3" right separator with-close-button>
        <div class="grid gap-5" @keydown.enter="$wire.showFilters = false">
            <x-input label="invoiceId ..." wire:model.live.debounce="invoiceId" icon="o-user" inline />
            <x-select label="Status" :options="$statuses" wire:model.live="status_id" icon="o-map-pin" placeholder="All"
                placeholder-value="0" inline />
          
        </div>

        {{-- ACTIONS --}}
        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.showFilters = false" />
        </x-slot:actions>
    </x-drawer>
</div>
