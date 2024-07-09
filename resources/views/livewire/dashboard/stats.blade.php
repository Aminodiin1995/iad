<?php

use App\Models\Invoice;
use Carbon\Carbon;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;

new class extends Component {
    #[Reactive]
    public string $period = '';

    public function mount(string $period)
    {
        $this->period = $period;
    }

    public function getInvoicesQuery()
    {
        $query = Invoice::query();

        switch ($this->period) {
            case '-7 days':
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
                break;
            case '-15 days':
                $query->where('created_at', '>=', Carbon::now()->subDays(15));
                break;
            case '-30 days':
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
                break;
            default:
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
                break;
        }

        return $query;
    }

    public function getStats(): array
    {
        $invoicesQuery = $this->getInvoicesQuery();
        $invoices = $invoicesQuery->get();

        $gross = $invoices->sum('amount_paid');
        $orders = $invoices->count();
        $invoicePaid = $invoices->where('status_id', 2)->count(); // Assuming status_id 2 means paid
        $invoiceUnpaid = $invoices->where('status_id', 1)->count(); // Assuming status_id 1 means unpaid

        return [
            'gross' => $gross,
            'orders' => $orders,
            'invoice_paid' => $invoicePaid,
            'invoice_unpaid' => $invoiceUnpaid,
        ];
    }

    public function with(): array
    {
        $stats = $this->getStats();
        return [
            'stats' => $stats,
        ];
    }
};
?>

<div>
    <div class="grid gap-5 lg:grid-cols-4 lg:gap-8">
        <x-stat :value="$stats['gross']" title="Revenue" icon="o-banknotes" class="truncate shadow text-ellipsis" />
        <x-stat :value="$stats['invoice_paid']" title="Paid Invoices" icon="c-check-badge" class="shadow" />
        <x-stat :value="$stats['invoice_unpaid']" title="Unpaid Invoices" icon="o-stop-circle" class="shadow" />
        <x-stat value="IAD" title="" icon="o-heart" color="!text-green-500" class="shadow" />

    </div>
</div>