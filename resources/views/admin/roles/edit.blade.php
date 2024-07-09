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
                    <a href="{{ route('admin.roles.index') }}"
                        class="px-4 py-2 bg-green-700 rounded-md hover:bg-green-500 text-slate-100">Role Index</a>
                </div>
                <div class="flex flex-col p-2 bg-slate-100">
                    <div class="w-1/2 mt-10 space-y-8 divide-y divide-gray-200">
                        <form method="POST" action="{{ route('admin.roles.update', $role->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="sm:col-span-6">
                                <label for="name" class="block text-sm font-medium text-gray-700"> Role name </label>
                                <div class="mt-1">
                                    <input type="text" id="name" name="name" value="{{ $role->name }}"
                                        class="block w-full px-3 py-2 text-base leading-normal transition duration-150 ease-in-out bg-white border border-gray-400 rounded-md appearance-none sm:text-sm sm:leading-5" />
                                </div>
                                @error('name')
                                    <span class="text-sm text-red-400">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="pt-5 sm:col-span-6">
                                <button type="submit"
                                    class="px-4 py-2 bg-green-500 rounded-md hover:bg-green-700">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="p-2 mt-6 bg-slate-100">
                    <h2 class="text-2xl font-semibold">Role Permissions</h2>
                    <div class="flex p-2 mt-4 space-x-2">
                        @if ($role->permissions)
                            @foreach ($role->permissions as $role_permission)
                                <form class="px-4 py-2 text-black bg-red-500 rounded-md hover:bg-red-700" method="POST"
                                    action="{{ route('admin.roles.permissions.revoke', [$role->id, $role_permission->id]) }}"
                                    onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">{{ $role_permission->name }}</button>
                                </form>
                            @endforeach
                        @endif
                    </div>
                    <div class="max-w-xl mt-6">
                        <form method="POST" action="{{ route('admin.roles.permissions', $role->id) }}">
                            @csrf
                            <div class="sm:col-span-6">
                                <label for="permission"
                                    class="block text-sm font-medium text-gray-700">Permission</label>
                                <select id="permission" name="permission" autocomplete="permission-name"
                                    class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @foreach ($permissions as $permission)
                                        <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('name')
                                <span class="text-sm text-red-400">{{ $message }}</span>
                            @enderror
                    </div>
                    <div class="pt-5 sm:col-span-6">
                        <button type="submit"
                            class="px-4 py-2 bg-green-500 rounded-md hover:bg-green-700">Assign</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
