<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gassiness') }}
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

                    <div class="mb-4">
                        <a href="{{ route('gassiness.create') }}" class="px-4 py-2 text-lg font-semibold text-white bg-blue-500 rounded-md hover:bg-blue-600">
                            Add Record
                        </a>
                    </div>

                    <div class="mt-6">
                        @foreach ($gassinesses as $gassiness)
                            <div class="flex items-center mb-4">
                                <label for="MPR_{{ $gassiness->id }}" class="w-40 text-lg font-semibold text-gray-800">MPR:</label>
                                <input type="text" name="MPR" id="MPR_{{ $gassiness->id }}" class="w-64 pl-2 py-2 border border-gray-300 rounded-md" value="{{ $gassiness->MPR }}" readonly>
                            </div>
                            <div class="flex items-center mb-4">
                                <label for="measurements_{{ $gassiness->id }}" class="w-40 text-lg font-semibold text-gray-800">Measurements:</label>
                                @foreach ($gassiness->measurements as $key => $measurement)
                                    <input type="text" name="measurements[]" id="measurements_{{ $gassiness->id }}_{{ $key }}" class="w-64 pl-2 py-2 border border-gray-300 rounded-md" value="{{ $measurement }}" readonly>
                                @endforeach
                            </div>
                            <div class="flex items-center mb-4">
                                <label for="device_{{ $gassiness->id }}" class="w-40 text-lg font-semibold text-gray-800">Device:</label>
                                <input type="text" name="device" id="device_{{ $gassiness->id }}" class="w-64 pl-2 py-2 border border-gray-300 rounded-md" value="{{ $gassiness->device }}" readonly>
                            </div>
                            <div class="flex items-center mb-4">
                                <label for="factory_number_{{ $gassiness->id }}" class="w-40 text-lg font-semibold text-gray-800">Factory Number:</label>
                                <input type="text" name="factory_number" id="factory_number_{{ $gassiness->id }}" class="w-64 pl-2 py-2 border border-gray-300 rounded-md" value="{{ $gassiness->factory_number }}" readonly>
                            </div>
                            <div class="flex items-center mb-4">
                                <label for="user_id_{{ $gassiness->id }}" class="w-40 text-lg font-semibold text-gray-800">User ID:</label>
                                <input type="text" name="user_id" id="user_id_{{ $gassiness->id }}" class="w-64 pl-2 py-2 border border-gray-300 rounded-md" value="{{ $gassiness->user_id }}" readonly>
                            </div>
                            <div class="flex items-center mb-4">
                                <label for="user_station_id_{{ $gassiness->id }}" class="w-40 text-lg font-semibold text-gray-800">User Station ID:</label>
                                <input type="text" name="user_station_id" id="user_station_id_{{ $gassiness->id }}" class="w-64 pl-2 py-2 border border-gray-300 rounded-md" value="{{ $gassiness->user_station_id }}" readonly>
                            </div>
                            <div class="flex justify-end">
                                <a href="{{ route('gassiness.edit', $gassiness->id) }}" class="inline-block mr-2 px-4 py-2 text-lg font-semibold text-gray-900 bg-green-200 rounded-md hover:bg-green-300">Edit</a>
                                <form action="{{ route('gassiness.destroy', $gassiness->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 text-lg font-semibold text-gray-900 bg-red-200 rounded-md hover:bg-red-300">Delete</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
