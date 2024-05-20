<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('stations.index') }}" class="hover:text-blue-700">{{ __('Stations') }}</a> / {{ __('Add Station') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('stations.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="label" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Label:') }}</label>
                            <input type="text" name="label" id="label" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                        </div>
                        <div class="mb-4">
                            <label for="city" class="block text-gray-700 text-sm font-bold mb-2">{{ __('City:') }}</label>
                            <input type="text" name="city" id="city" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                        </div>
                        <div class="mb-4">
                            <label for="region" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Region:') }}</label>
                            <input type="text" name="region" id="region" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                        </div>
                        <div class="mb-4">
                            <label for="type" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Type:') }}</label>
                            <input type="text" name="type" id="type" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                        </div>
                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Add Station') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
