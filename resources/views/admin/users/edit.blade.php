<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('admin.users.index') }}" class="hover:text-blue-700">{{ __('Users') }}</a>
            / {{ __('Edit User') }} : {{ $user->name }} {{ $user->surname }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="post" action="{{ route('admin.users.update', $user) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('patch')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')"/>
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                          :value="old('name', $user->name)"
                                          required autofocus autocomplete="name"/>
                            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                        </div>

                        <!-- Surname -->
                        <div>
                            <x-input-label for="surname" :value="__('Surname')"/>
                            <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname"
                                          :value="old('surname', $user->surname)" required autofocus
                                          autocomplete="surname"/>
                            <x-input-error :messages="$errors->get('surname')" class="mt-2"/>
                        </div>

                        <!-- Patronymic -->
                        <div>
                            <x-input-label for="patronymic" :value="__('Patronymic')"/>
                            <x-text-input id="patronymic" class="block mt-1 w-full" type="text" name="patronymic"
                                          :value="old('patronymic', $user->patronymic)" required autofocus
                                          autocomplete="patronymic"/>
                            <x-input-error :messages="$errors->get('patronymic')" class="mt-2"/>
                        </div>

                        <!-- Station -->
                        <div>
                            <x-input-label for="station_id" :value="__('Station')"/>
                            <select id="station_id" name="station_id" class="block mt-1 w-full" required>
                                @foreach($stations as $station)
                                    <option
                                        value="{{ $station->id }}" {{ $station->id == $user->station_id ? 'selected' : '' }}>
                                        {{ $station->label }}, {{ $station->city }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('station_id')" class="mt-2"/>
                        </div>

                        <!-- Roles -->
                        <div class="mb-4">
                            <x-input-label for="roles" :value="__('Roles')"/>
                            <div class="mt-2 space-x-2">
                                @foreach($roles as $role)
                                    <div class="inline-flex items-center">
                                        <input type="checkbox" id="role_{{ $role->id }}" name="roles[]"
                                               value="{{ $role->id }}"
                                               {{ in_array($role->id, $userRoles->pluck('id')->toArray()) ? 'checked' : '' }} class="mr-1">
                                        <label for="role_{{ $role->id }}">{{ __($role->name) }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('roles')" class="mt-2"/>
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')"/>
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                          :value="old('email', $user->email)" required autocomplete="username"/>
                            <x-input-error class="mt-2" :messages="$errors->get('email')"/>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>

                            @if (session('status') === 'profile-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600"
                                >{{ __('Saved.') }}</p>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
