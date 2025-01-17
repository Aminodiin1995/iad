<?php

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;


new
#[Layout('components.app')]
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

<x-card>

                    <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 bg-green-700 rounded-md hover:bg-green-500">Create Role</a>
                </div>
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Edit</th>
                                <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($roles as $role)
                                <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    {{ $role->name }}
                                </div>
                                </td>
                                <td>
                                    <div class="flex justify-end">
                                        <div class="flex space-x-2">
                                         <a href="{{ route('admin.roles.edit', $role->id) }}" class="px-4 py-2 text-black bg-blue-500 rounded-md hover:bg-blue-700">Edit</a>
                                       
                                        </div>
                                    </div>
                                </td>
                            </tr>  
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                    </div>
                </div>
  
            </div>
        </div>
    </div>

</x-card>




