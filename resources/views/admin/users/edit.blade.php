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
                    <form method="post" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('patch')

                        <!-- Name -->
                        <div class="flex items-center mb-4">
                            <label for="name"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Name') }}</label>
                            <x-text-input id="name" name="name" type="text" class="block w-full"
                                          :value="old('name', $user->name)"
                                          required autofocus autocomplete="name"/>
                            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                        </div>

                        <!-- Surname -->
                        <div class="flex items-center mb-4">
                            <label for="surname"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Surname') }}</label>
                            <x-text-input id="surname" class="block w-full" type="text" name="surname"
                                          :value="old('surname', $user->surname)" required autofocus
                                          autocomplete="surname"/>
                            <x-input-error :messages="$errors->get('surname')" class="mt-2"/>
                        </div>

                        <!-- Patronymic -->
                        <div class="flex items-center mb-4">
                            <label for="patronymic"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Patronymic') }}</label>
                            <x-text-input id="patronymic" class="block w-full" type="text" name="patronymic"
                                          :value="old('patronymic', $user->patronymic)" required autofocus
                                          autocomplete="patronymic"/>
                            <x-input-error :messages="$errors->get('patronymic')" class="mt-2"/>
                        </div>

                        <!-- Station -->
                        <div class="flex items-center mb-4">
                            <label for="station_id"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Station') }}</label>
                            <select id="station_id" name="station_id" class="block w-full" required>
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
                        <div class="flex items-center mb-4">
                            <label for="roles"
                                   class="w-max text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 mr-4 rounded-l-md">{{ __('Roles') }}</label>
                            <div class="space-x-2">
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
                        <div class="flex items-center mb-4">
                            <label for="email"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Email') }}</label>
                            <x-text-input id="email" name="email" type="email" class="block w-full"
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
