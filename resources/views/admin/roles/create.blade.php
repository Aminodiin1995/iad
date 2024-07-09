<?php

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;


new
#[Layout('components.layouts.empty')]
#[Title('roles')]
class extends Component {
    public $roles;

public function mount() {
    $this->roles = Role::all();
}
public function with(): array
    {
        return [

            'Roles' => Role::all(),

        ];
    }

}; ?>

    <div class="w-full py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-2 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="flex p-2">
                    <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-green-700 rounded-md hover:bg-green-500 text-slate-100">Role Index</a>
                </div>
                <div class="flex flex-col">
                    <div class="w-1/2 mt-10 space-y-8 divide-y divide-gray-200">
                        <form method="POST" action="{{ route('admin.roles.store') }}">
                            @csrf
                          <div class="sm:col-span-6">
                            <label for="name" class="block text-sm font-medium text-gray-700"> Role name </label>
                            <div class="mt-1">
                              <input type="text" id="name" name="name" class="block w-full px-3 py-2 text-base leading-normal transition duration-150 ease-in-out bg-white border border-gray-400 rounded-md appearance-none sm:text-sm sm:leading-5" />
                            </div>
                            @error('name') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                          </div>
                          <div class="pt-5 sm:col-span-6">
                            <button type="submit" class="px-4 py-2 bg-green-500 rounded-md hover:bg-green-700">Create</button>
                          </div>
                        </form>
                      </div>
                      
                </div>
  
            </div>
        </div>
    </div>
