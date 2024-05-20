<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Journal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('journals.update', $journal) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="pressure_in" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Pressure In') }}
                            </label>
                            <input type="number" step="0.01" name="pressure_in" id="pressure_in" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $journal->pressure_in }}">
                            @error('pressure_in')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="pressure_out_1" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Pressure Out 1') }}
                            </label>
                            <input type="number" step="0.01" name="pressure_out_1" id="pressure_out_1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $journal->pressure_out_1 }}">
                            @error('pressure_out_1')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="pressure_out_2" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Pressure Out 2') }}
                            </label>
                            <input type="number" step="0.01" name="pressure_out_2" id="pressure_out_2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $journal->pressure_out_2 }}">
                            @error('pressure_out_2')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="temperature_1" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Temperature 1') }}
                            </label>
                            <input type="number" step="0.01" name="temperature_1" id="temperature_1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $journal->temperature_1 }}">
                            @error('temperature_1')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="temperature_2" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Temperature 2') }}
                            </label>
                            <input type="number" step="0.01" name="temperature_2" id="temperature_2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $journal->temperature_2 }}">
                            @error('temperature_2')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="odorant_value_1" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Odorant Value 1') }}
                            </label>
                            <input type="number" step="0.01" name="odorant_value_1" id="odorant_value_1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $journal->odorant_value_1 }}">
                            @error('odorant_value_1')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="odorant_value_2" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Odorant Value 2') }}
                            </label>
                            <input type="number" step="0.01" name="odorant_value_2" id="odorant_value_2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $journal->odorant_value_2 }}">
                            @error('odorant_value_2')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="gas_heater_temperature_in" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Gas Heater Temperature In') }}
                            </label>
                            <input type="number" step="0.01" name="gas_heater_temperature_in" id="gas_heater_temperature_in" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $journal->gas_heater_temperature_in }}">
                            @error('gas_heater_temperature_in')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="gas_heater_temperature_out" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Gas Heater Temperature Out') }}
                            </label>
                            <input type="number" step="0.01" name="gas_heater_temperature_out" id="gas_heater_temperature_out" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $journal->gas_heater_temperature_out }}">
                            @error('gas_heater_temperature_out')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
