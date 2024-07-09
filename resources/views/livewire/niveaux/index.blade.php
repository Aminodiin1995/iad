<?php

use App\Models\Niveau;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component
{
    use WithPagination, Toast;

    public function niveaux()
    {
        return Niveau::paginate(10);
    }

    public function deleteNiveau($id)
    {
        $niveau = Niveau::findOrFail($id);
        $niveau->delete();
        $this->toast('Niveau deleted successfully.', 'info', 'bottom-left', 'o-warning');
    }

    public function with(): array
    {
        return [
            'niveaux' => $this->niveaux(),
        ];
    }

    protected $listeners = ['niveauDeleted' => '$refresh'];
};
?>
<div>
    <x-header title="Niveaux" separator progress-indicator>
        <x-slot:middle class="!justify-end"></x-slot:middle>

        <x-slot:actions>
            <x-button label="Add Niveau" icon="o-plus" link="{{ route('niveaux.create') }}" class="btn-primary" responsive />
        </x-slot:actions>
    </x-header>

    <x-card>
        <table class="min-w-full bg-white">
            <tbody class="text-sm font-light text-gray-600">
                @foreach ($niveaux as $niveau)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-4 py-2">{{ $niveau->id }}</td>
                        <td class="px-4 py-2">{{ $niveau->name }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('niveaux.edit', $niveau->id) }}" class="p-2 text-white bg-yellow-500 rounded">Edit</a>
                            <button
                                onclick="confirmDelete({{ $niveau->id }})"
                                class="p-2 text-white bg-red-500 rounded"
                            >Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $niveaux->links() }}
    </x-card>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this niveau?')) {
            @this.call('deleteNiveau', id);
        }
    }
</script>
