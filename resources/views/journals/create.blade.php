<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('journals.index') }}" class="hover:text-blue-700">{{ __('Journals') }}</a>
            / {{ __('Add Journal') }}
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
                    <form method="POST" action="{{ route('journals.store') }}">
                        @csrf

                        <div class="flex items-center mb-4">
                            <label for="pressure_in"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Pressure In') }}
                                :</label>
                            <div class="flex w-64">
                                <input type="number" step="0.01" name="pressure_in" id="pressure_in"
                                       class="w-full py-2 px-4 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('pressure_in') }}"/>
                                <span class="inline-flex items-center px-3 text-gray-700 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md">Kg/cm²</span>
                            </div>
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="pressure_out_1"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Pressure Out 1') }}
                                :</label>
                            <div class="flex w-64">
                                <input type="number" step="0.01" name="pressure_out_1" id="pressure_out_1"
                                       class="w-full py-2 px-4 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('pressure_out_1') }}"/>
                                <span class="inline-flex items-center px-3 text-gray-700 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md">Kg/cm²</span>
                            </div>
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="pressure_out_2"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Pressure Out 2') }}
                                :</label>
                            <div class="flex w-64">
                                <input type="number" step="0.01" name="pressure_out_2" id="pressure_out_2"
                                       class="w-full py-2 px-4 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('pressure_out_2') }}"/>
                                <span class="inline-flex items-center px-3 text-gray-700 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md">Kg/cm²</span>
                            </div>
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="temperature_1"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Temperature 1') }}
                                :</label>
                            <div class="flex w-64">
                                <input type="number" step="0.01" name="temperature_1" id="temperature_1"
                                       class="w-full py-2 px-4 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('temperature_1') }}"/>
                                <span class="inline-flex items-center px-3 text-gray-700 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md">°C</span>
                            </div>
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="temperature_2"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Temperature 2') }}
                                :</label>
                            <div class="flex w-64">
                                <input type="number" step="0.01" name="temperature_2" id="temperature_2"
                                       class="w-full py-2 px-4 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('temperature_2') }}"/>
                                <span class="inline-flex items-center px-3 text-gray-700 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md">°C</span>
                            </div>
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="odorant_value_1"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Odorant Value 1') }}
                                :</label>
                            <div class="flex w-64">
                                <input type="number" step="0.01" name="odorant_value_1" id="odorant_value_1"
                                       class="w-full py-2 px-4 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('odorant_value_1') }}"/>
                                <span class="inline-flex items-center px-3 text-gray-700 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md">mg/m³</span>
                            </div>
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="odorant_value_2"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Odorant Value 2') }}
                                :</label>
                            <div class="flex w-64">
                                <input type="number" step="0.01" name="odorant_value_2" id="odorant_value_2"
                                       class="w-full py-2 px-4 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('odorant_value_2') }}"/>
                                <span class="inline-flex items-center px-3 text-gray-700 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md">mg/m³</span>
                            </div>
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="gas_heater_temperature_in"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Gas Heater Temperature In') }}
                                :</label>
                            <div class="flex w-64">
                                <input type="number" step="0.01" name="gas_heater_temperature_in"
                                       id="gas_heater_temperature_in"
                                       class="w-full py-2 px-4 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('gas_heater_temperature_in') }}"/>
                                <span class="inline-flex items-center px-3 text-gray-700 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md">°C</span>
                            </div>
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="gas_heater_temperature_out"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Gas Heater Temperature Out') }}
                                :</label>
                            <div class="flex w-64">
                                <input type="number" step="0.01" name="gas_heater_temperature_out"
                                       id="gas_heater_temperature_out"
                                       class="w-full py-2 px-4 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('gas_heater_temperature_out') }}"/>
                                <span class="inline-flex items-center px-3 text-gray-700 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md">°C</span>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Create record') }}
                            </button>
                            <button type="button" onclick="window.location.href='{{ route('journals.index') }}'" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Back') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
