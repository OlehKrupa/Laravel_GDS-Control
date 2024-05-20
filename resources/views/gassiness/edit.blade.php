<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Gassiness') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 text-red-600">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('gassiness.update', $gassiness->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="flex items-center mb-4">
                            <label for="MPR" class="w-40 text-lg font-semibold text-gray-800">MPR:</label>
                            <input type="text" name="MPR" id="MPR" class="w-64 pl-2 py-2 border border-gray-300 rounded-md" value="{{ $gassiness->MPR }}">
                        </div>
                        <div class="flex items-center mb-4">
                            <label for="measurements" class="w-40 text-lg font-semibold text-gray-800">Measurements:</label>
                            @foreach ($gassiness->measurements as $key => $measurement)
                                <input type="text" name="measurements[]" id="measurements_{{ $key }}" class="w-64 pl-2 py-2 border border-gray-300 rounded-md" value="{{ $measurement }}">
                            @endforeach
                        </div>
                        <div class="flex items-center mb-4">
                            <label for="device" class="w-40 text-lg font-semibold text-gray-800">Device:</label>
                            <input type="text" name="device" id="device" class="w-64 pl-2 py-2 border border-gray-300 rounded-md" value="{{ $gassiness->device }}">
                        </div>
                        <div class="flex items-center mb-4">
                            <label for="factory_number" class="w-40 text-lg font-semibold text-gray-800">Factory Number:</label>
                            <input type="text" name="factory_number" id="factory_number" class="w-64 pl-2 py-2 border border-gray-300 rounded-md" value="{{ $gassiness->factory_number }}">
                        </div>
                        <div class="flex items-center mb-4">
                            <label for="user_id" class="w-40 text-lg font-semibold text-gray-800">User ID:</label>
                            <input type="text" name="user_id" id="user_id" class="w-64 pl-2 py-2 border border-gray-300 rounded-md" value="{{ $gassiness->user_id }}">
                        </div>
                        <div class="flex items-center mb-4">
                            <label for="user_station_id" class="w-40 text-lg font-semibold text-gray-800">User Station ID:</label>
                            <input type="text" name="user_station_id" id="user_station_id" class="w-64 pl-2 py-2 border border-gray-300 rounded-md" value="{{ $gassiness->user_station_id }}">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 text-lg font-semibold text-gray-900 bg-green-200 rounded-md hover:bg-green-300">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
