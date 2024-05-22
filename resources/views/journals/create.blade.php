<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('journals.index') }}" class="hover:text-blue-700">{{ __('Journals') }}</a>
            / {{ __('Create Journal') }}
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
                        <form method="POST" action="{{ route('journals.store') }}">
                            @csrf

                            <div class="flex items-center mb-4">
                                <label for="pressure_in"
                                       class="w-40 text-lg font-semibold text-gray-800">{{ __('Pressure In') }}:</label>
                                <input type="number" step="0.01" name="pressure_in" id="pressure_in"
                                       class="w-64 pl-2 py-2 border border-gray-300 rounded-md"
                                       value="{{ old('pressure_in') }}"/>

                            </div>

                            <div class="flex items-center mb-4">
                                <label for="pressure_out_1"
                                       class="w-40 text-lg font-semibold text-gray-800">{{ __('Pressure Out 1') }}:</label>
                                <input type="number" step="0.01" name="pressure_out_1" id="pressure_out_1"
                                       class="w-64 pl-2 py-2 border border-gray-300 rounded-md"
                                       value="{{ old('pressure_out_1') }}"/>

                            </div>

                            <div class="flex items-center mb-4">
                                <label for="pressure_out_2"
                                       class="w-40 text-lg font-semibold text-gray-800">{{ __('Pressure Out 2') }}:</label>
                                <input type="number" step="0.01" name="pressure_out_2" id="pressure_out_2"
                                       class="w-64 pl-2 py-2 border border-gray-300 rounded-md"
                                       value="{{ old('pressure_out_2') }}"/>

                            </div>

                            <div class="flex items-center mb-4">
                                <label for="temperature_1"
                                       class="w-40 text-lg font-semibold text-gray-800">{{ __('Temperature 1') }}:</label>
                                <input type="number" step="0.01" name="temperature_1" id="temperature_1"
                                       class="w-64 pl-2 py-2 border border-gray-300 rounded-md"
                                       value="{{ old('temperature_1') }}"/>

                            </div>

                            <div class="flex items-center mb-4">
                                <label for="temperature_2"
                                       class="w-40 text-lg font-semibold text-gray-800">{{ __('Temperature 2') }}:</label>
                                <input type="number" step="0.01" name="temperature_2" id="temperature_2"
                                       class="w-64 pl-2 py-2 border border-gray-300 rounded-md"
                                       value="{{ old('temperature_2') }}"/>

                            </div>

                            <div class="flex items-center mb-4">
                                <label for="odorant_value_1"
                                       class="w-40 text-lg font-semibold text-gray-800">{{ __('Odorant Value 1') }}:</label>
                                <input type="number" step="0.01" name="odorant_value_1" id="odorant_value_1"
                                       class="w-64 pl-2 py-2 border border-gray-300 rounded-md"
                                       value="{{ old('odorant_value_1') }}"/>

                            </div>

                            <div class="flex items-center mb-4">
                                <label for="odorant_value_2"
                                       class="w-40 text-lg font-semibold text-gray-800">{{ __('Odorant Value 2') }}:</label>
                                <input type="number" step="0.01" name="odorant_value_2" id="odorant_value_2"
                                       class="w-64 pl-2 py-2 border border-gray-300 rounded-md"
                                       value="{{ old('odorant_value_2') }}"/>

                            </div>

                            <div class="flex items-center mb-4">
                                <label for="gas_heater_temperature_in"
                                       class="w-40 text-lg font-semibold text-gray-800">{{ __('Gas Heater Temperature In') }}:</label>
                                <input type="number" step="0.01" name="gas_heater_temperature_in"
                                       id="gas_heater_temperature_in"
                                       class="w-64 pl-2 py-2 border border-gray-300 rounded-md"
                                       value="{{ old('gas_heater_temperature_in') }}"/>

                            </div>

                            <div class="flex items-center mb-4">
                                <label for="gas_heater_temperature_out"
                                       class="w-40 text-lg font-semibold text-gray-800">{{ __('Gas Heater Temperature Out') }}:</label>
                                <input type="number" step="0.01" name="gas_heater_temperature_out"
                                       id="gas_heater_temperature_out"
                                       class="w-64 pl-2 py-2 border border-gray-300 rounded-md"
                                       value="{{ old('gas_heater_temperature_out') }}"/>
                            </div>

                            <div>
                                <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('Create Journal') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
