<?php
use App\Models\Student;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Section;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Carbon\Carbon;

new class extends Component {
    use Toast;

    public $filiere_id;
    public $niveau_id;
    public $section_id;
    public $from;
    public $to;
    public $results = [];

    public function search()
    {
        $query = Student::query()
            ->with('filiere', 'niveau', 'section');

        if ($this->from) {
            $query->whereDate('created_at', '>=', Carbon::parse($this->from)->startOfDay());
        }

        if ($this->to) {
            $query->whereDate('created_at', '<=', Carbon::parse($this->to)->endOfDay());
        }

        if ($this->filiere_id) {
            $query->where('filiere_id', $this->filiere_id);
        }

        if ($this->niveau_id) {
            $query->where('niveau_id', $this->niveau_id);
        }

        if ($this->section_id) {
            $query->where('section_id', $this->section_id);
        }

        $this->results = $query->get();
    }

    public function with(): array
    {
        return [
            'filieres' => Filiere::all(),
            'niveaux' => Niveau::all(),
            'sections' => Section::all(),
        ];
    }
};
?>
<div>
  

    <div class="flex items-center justify-center h-screen bg-gray-100">
        < <div class="mx-auto overflow-hidden bg-white rounded-lg shadow-md max-wtext-xl font-semibold-md">
            <div class="p-6 text-center text-white bg-green-400">
                <p class="text-2xl font-semibold">IAD</p>
                <h1 class="text-xl font-semibold">Thank you for your business.</h1>
            </div>
            <div class="p-6">
                <div class="flex justify-between mb-4">
                    <div>
                        <p class="font-semibold">Billed to</p>
                        <p>Freddy Fakename</p>
                        <p>345 9th Street West</p>
                        <p>EL Dorado, AR 717130</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold">Payment date</p>
                        <p>12/01/2016</p>
                    </div>
                </div>
                <div class="py-2 mb-4 border-t border-b">
                    <div class="flex justify-between mb-2">
                        <p class="font-semibold">Service</p>
                        <p class="font-semibold">Amount</p>
                    </div>
                    <div class="flex justify-between">
                        <p>Frais Scolaire</p>
                        <p>$ USD</p>
                    </div>
                </div>
                <div class="flex justify-between font-semibold">
                    <p>Amount paid</p>
                    <p>$450.00 USD</p>
                </div>
            </div>
            <div class="p-4 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m-6 6V3m0 6h6M4 3h4a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2z" />
                </svg>
            </div>
        </div>
</div>
