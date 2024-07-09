<?php

use App\Models\Section;
use Livewire\Volt\Component;

new class extends Component
{
    public Section $section;
    public string $name;

    public function mount(Section $section)
    {
        $this->section = $section;
        $this->name = $section->name;
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->section->update([
            'name' => $this->name,
        ]);


        return redirect()->route('sections.index');
    }
};
?>

<div>
    <div>
        <h1 class="mb-4 text-2xl font-bold">Edit Section</h1>
        <hr />
    
        <form wire:submit.prevent="submit">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Name</label>
                <x-input type="text" id="name" wire:model="name" class="border border-gray-300 rounded" required />
            </div>
    
            <button type="submit" class="p-2 text-white bg-blue-500 rounded">Update</button>
        </form>
    </div>
</div>
