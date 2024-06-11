<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('admin.users.index') }}" class="hover:text-blue-700">{{ __('Users') }}</a>
            / {{ __('Register User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <!-- Name -->
                        <div class="flex items-center mb-4">
                            <label for="name" class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-md">{{ __('Name') }}</label>
                            <x-text-input id="name" class="block w-full" type="text" name="name"
                                          :value="old('name')" required autofocus autocomplete="name"/>
                            <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                        </div>

                        <!-- Surname -->
                        <div class="flex items-center mb-4">
                            <label for="surname" class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-md">{{ __('Surname') }}</label>
                            <x-text-input id="surname" class="block w-full" type="text" name="surname"
                                          :value="old('surname')" required autofocus autocomplete="surname"/>
                            <x-input-error :messages="$errors->get('surname')" class="mt-2"/>
                        </div>

                        <!-- Patronymic -->
                        <div class="flex items-center mb-4">
                            <label for="patronymic" class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-md">{{ __('Patronymic') }}</label>
                            <x-text-input id="patronymic" class="block w-full" type="text" name="patronymic"
                                          :value="old('patronymic')" required autofocus autocomplete="patronymic"/>
                            <x-input-error :messages="$errors->get('patronymic')" class="mt-2"/>
                        </div>

                        <!-- Station -->
                        <div class="flex items-center mb-4">
                            <label for="station_id" class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-md">{{ __('Station') }}</label>
                            <select id="station_id" name="station_id" class="block w-full" required>
                                <option value="">{{ __('Select a station') }}</option>
                                @foreach($stations as $station)
                                    <option value="{{ $station->id }}">{{ $station->label }}
                                        , {{ $station->city }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('station_id')" class="mt-2"/>
                        </div>

                        <!-- Roles -->
                        <div class="flex items-center mb-4">
                            <label for="roles" class="w-max text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 mr-4 rounded-md">{{ __('Roles') }}</label>
                            <div class="space-x-2">
                                @foreach($roles as $role)
                                    <div class="inline-flex items-center">
                                        <input type="checkbox" id="role_{{ $role->id }}" name="roles[]"
                                               value="{{ $role->id }}" class="mr-1">
                                        <label for="role_{{ $role->id }}">{{ $role->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('roles')" class="mt-2"/>
                        </div>

                        <!-- Email Address -->
                        <div class="flex items-center mb-4">
                            <label for="email" class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-md">{{ __('Email') }}</label>
                            <x-text-input id="email" class="block w-full" type="email" name="email"
                                          :value="old('email')" required autocomplete="username"/>
                            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                        </div>

                        <!-- Password -->
                        <div class="flex items-center mb-4">
                            <label for="password" class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-md">{{ __('Password') }}</label>
                            <x-text-input id="password" class="block w-full" type="password" name="password"
                                          required autocomplete="new-password"/>
                            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                        </div>

                        <!-- Confirm Password -->
                        <div class="flex items-center mb-4">
                            <label for="password_confirmation" class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-md">{{ __('Confirm Password') }}</label>
                            <x-text-input id="password_confirmation" class="block w-full" type="password"
                                          name="password_confirmation" required autocomplete="new-password"/>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Register User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
