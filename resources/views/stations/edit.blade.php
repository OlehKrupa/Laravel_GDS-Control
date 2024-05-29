<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('stations.index') }}" class="hover:text-blue-700">{{ __('Stations') }}</a>
            / {{ __('Update Station') }}
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
                    <form method="POST" action="{{ route('stations.update', $station->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="flex items-center mb-4">
                            <label for="label"
                                   class="w-20 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Label:') }}</label>
                            <input type="text" name="label" id="label"
                                   class="w-64 py-2 px-4 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ $station->label }}"/>
                        </div>
                        <div class="flex items-center mb-4">
                            <label for="city"
                                   class="w-20 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('City:') }}</label>
                            <input type="text" name="city" id="city"
                                   class="w-64 py-2 px-4 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ $station->city }}"/>
                        </div>
                        <div class="flex items-center mb-4">
                            <label for="region"
                                   class="w-20 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Region:') }}</label>
                            <input type="text" name="region" id="region"
                                   class="w-64 py-2 px-4 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ $station->region }}"/>
                        </div>
                        <div class="flex items-center mb-4">
                            <label for="type"
                                   class="w-20 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Type:') }}</label>
                            <input type="text" name="type" id="type"
                                   class="w-64 py-2 px-4 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ $station->type }}"/>
                        </div>

                        <div>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
