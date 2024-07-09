<?php

use App\Models\BillMethod;
use App\Models\Status;
use App\Models\Student;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public Student $student;
   

    public function mount(Student $student): void
    {
        $this->student = $student;
      

    }
   

    public function with(): array
    {
        return [
            'billMethods' => BillMethod::all(),
            'statuses' => Status::all(),
        ];
    }
};
?>


<div>
    <div class="max-w-4xl p-6 mx-auto bg-white rounded-lg shadow-md">
        <div class="text-right">
            <p>Djibouti, le {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        </div>

        <div class="mt-4">
            <p><span class="font-bold">Nom :</span> {{ $student->name }}</p>
            <p><span class="font-bold">Adresse :</span> {{ $student->address }}</p>
            <p><span class="font-bold">Mobile :</span> {{ $student->telephone }}</p>
        </div>

        <div class="my-8 text-center">
            <p>A</p>
            <p class="font-bold">Monsieur le Directeur de L’Institut Africain Djibouti</p>
        </div>

        <div class="mt-4">
            <p><span class="font-bold">Objet :</span> Lettre d’engagement « Paiement »</p>
        </div>

        <div class="mt-4">
            <p>Monsieur,</p>
            <p class="mt-4">
                Je soussigné <span class="font-bold">{{ $student->father_name }}</span> (père) de l’étudiante <span class="font-bold">{{ $student->name }}</span> en Licence 2 Génie Informatique à l’Institut Africain de Djibouti vient par le présent document expliquer le paiement des frais de scolarité de <span class="font-bold">350 000 FDJ (Trois cent cinquante mille FDJ)</span> de l’année Académique 2023-2024 échelonné selon le tableau ci-dessous :
            </p>
        </div>

        <div class="mt-8 overflow-x-auto">
            <table class="min-w-full border border-collapse">
                <thead>
                    <tr>
                        @foreach ($student->invoices as $invoice)
                        <td class="p-2 text-left border">{{ $invoice->start_date }}</td>
                        @endforeach
                        
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($student->invoices as $invoice)
                        <th class="p-2 font-medium text-left border">{{ $invoice->amount }} fdj</th>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between p-10 mt-8">
            <div>
                <p>Autorisé</p>
                <p>Par la Comptabilité de l’IAD</p>
            </div>
            <div class="text-right">
                <p>L’intéressé(e)</p>
                <p>Abdoulkader Ali Nour</p>
            </div>
        </div>
    </div>
</div>


