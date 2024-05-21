<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('stations.index') }}" class="hover:text-blue-700">{{ __('Stations') }}</a> / {{ __('Edit Station') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
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
                        <div class="mb-4">
                            <label for="label" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Label:') }}</label>
                            <input type="text" name="label" id="label" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $station->label }}" />
                        </div>
                        <div class="mb-4">
                            <label for="city" class="block text-gray-700 text-sm font-bold mb-2">{{ __('City:') }}</label>
                            <input type="text" name="city" id="city" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $station->city }}" />
                        </div>
                        <div class="mb-4">
                            <label for="region" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Region:') }}</label>
                            <input type="text" name="region" id="region" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $station->region }}" />
                        </div>
                        <div class="mb-4">
                            <label for="type" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Type:') }}</label>
                            <input type="text" name="type" id="type" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $station->type }}" />
                        </div>
                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Update Station') }}
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
