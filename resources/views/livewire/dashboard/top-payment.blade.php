<?php

use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;

new class extends Component {
    #[Reactive]
    public string $period = '-30 days';

    public function topPayments(): Collection
    {
        return Payment::query()
            ->with('invoice.student')
            ->selectRaw("sum(amount) as total_amount, invoice_id, payment_type")
            ->where('created_at', '>=', Carbon::parse($this->period)->startOfDay())
            ->groupBy('invoice_id', 'payment_type')
            ->orderByDesc('total_amount')
            ->take(10)
            ->get()
            ->map(function (Payment $payment) {
                $payment->total_amount = $payment->total_amount; // Store the total amount in the Payment model
                $payment->payment_type = $payment->payment_type; // Store the total amount in the Payment model
                return $payment;
            });
    }

    public function with(): array
    {
        return [
            'topPayments' => $this->topPayments(),
        ];
    }
};
?>

<div>
    <x-card title="Best Payments" separator shadow>
        <x-slot:menu>
            <x-button label="Payments" icon-right="o-arrow-right" link="/payments" class="btn-ghost btn-sm" />
        </x-slot:menu>

        @foreach($topPayments as $payment)
            @if($payment->invoice && $payment->invoice->student)
                   
                    <x-slot:actions>
                        <x-badge :value="$payment->total_amount" class="font-bold" />
                    </x-slot:actions>
                
            @endif
        @endforeach
    </x-card>
</div>
