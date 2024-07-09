<?php

use App\Models\Section;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component
{
    use WithPagination, Toast;

    public function sections()
    {
        return Section::paginate(10);
    }

    public function deleteSection($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();
        $this->toast('Section deleted successfully.', 'Section deleted successfully.', '', 'o-warning');
    }

    public function with(): array
    {
        return [
            'sections' => $this->sections(),
        ];
    }

    protected $listeners = ['sectionDeleted' => '$refresh'];
};
?>

<div>
    <x-header title="Sections" separator progress-indicator>
        <x-slot:middle class="!justify-end"></x-slot:middle>

        <x-slot:actions>
            <x-button label="Add Section" icon="o-plus" link="{{ route('sections.create') }}" class="btn-primary" responsive />
        </x-slot:actions>
    </x-header>

    <x-card>
        <table class="min-w-full bg-white">
            <tbody class="text-sm font-light text-gray-600">
                @foreach ($sections as $section)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-4 py-2">{{ $section->id }}</td>
                        <td class="px-4 py-2">{{ $section->name }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('sections.edit', $section->id) }}" class="p-2 text-white bg-yellow-500 rounded">Edit</a>
                            <button
                                onclick="confirmDelete({{ $section->id }})"
                                class="p-2 text-white bg-red-500 rounded"
                            >Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $sections->links() }}
    </x-card>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this section?')) {
            @this.call('deleteSection', id);
        }
    }
</script>

