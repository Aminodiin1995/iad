<?php

use App\Models\Filiere;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component
{
    use WithPagination, Toast;

    public function filieres()
    {
        return Filiere::paginate(10);
    }

    public function deleteFiliereConfirmed($id)
    {
        $filiere = Filiere::findOrFail($id);
        $filiere->delete();
        $this->toast('Filiere deleted successfully.', 'info', 'bottom-left', 'o-warning');
    }

    public function with(): array
    {
        return [
            'filieres' => $this->filieres(),
        ];
    }

    protected $listeners = ['filiereDeleted' => '$refresh'];
};
?>
<div>
    <x-header title="Filieres" separator progress-indicator>
        <x-slot:middle class="!justify-end"></x-slot:middle>

        <x-slot:actions>
            <x-button label="Add Filiere" icon="o-plus" link="/filieres/create" class="btn-primary" responsive />
        </x-slot:actions>
    </x-header>

    <x-card>
        <table class="min-w-full bg-white">
            <tbody class="text-sm font-light text-gray-600">
                @foreach ($filieres as $filiere)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-4 py-2">{{ $filiere->id }}</td>
                        <td class="px-4 py-2">{{ $filiere->name }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('filieres.edit', $filiere->id) }}" class="p-2 text-white bg-yellow-500 rounded">Edit</a>
                            <button wire:click="deleteFiliereConfirmed({{ $filiere->id }})" class="p-2 text-white bg-red-500 rounded">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $filieres->links() }}
    </x-card>
</div>
