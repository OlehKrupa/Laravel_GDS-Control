<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-4 text-red-600">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mt-6">
                        @foreach ($settings as $setting)
                            <div class="flex items-center mb-4">
                                <label for="value_{{ $setting->id }}" class="w-40 text-base font-semibold text-gray-800 bg-gray-200 py-2 px-3 rounded-l-md border border-gray-300 flex items-center">{{ $setting->label }}</label>
                                <form id="update-{{ $setting->id }}" method="POST" action="{{ route('settings.update', $setting->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" step="0.01" name="value" id="value_{{ $setting->id }}" class="w-64 pl-2 py-2 border border-gray-300 rounded-r-md" value="{{ $setting->value }}">
                                    <button type="submit" class="ml-2 px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-700">{{ __('Update') }}</button>
                                </form>
                                <form id="delete-{{ $setting->id }}" method="POST" action="{{ route('settings.destroy', $setting->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ml-2 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700" onclick="return confirm('{{ __('Are you sure you want to delete this setting?') }}')">{{ __('Delete') }}</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
