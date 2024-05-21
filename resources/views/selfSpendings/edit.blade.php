<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('selfSpendings.index') }}" class="hover:text-blue-700">{{ __('Self Spendings') }}</a>
            / {{ __('Edit Self Spending') }}
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
                        <form method="POST" action="{{ route('selfSpendings.update', $selfSpending->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label for="heater_time"
                                       class="block text-gray-700 text-sm font-bold mb-2">{{ __('Heater Time:') }}</label>
                                <input type="text" name="heater_time" id="heater_time"
                                       class="form-input rounded-md shadow-sm mt-1 block w-full"
                                       value="{{ $selfSpending->heater_time }}"/>
                            </div>
                            <div class="mb-4">
                                <label for="boiler_time"
                                       class="block text-gray-700 text-sm font-bold mb-2">{{ __('Boiler Time:') }}</label>
                                <input type="text" name="boiler_time" id="boiler_time"
                                       class="form-input rounded-md shadow-sm mt-1 block w-full"
                                       value="{{ $selfSpending->boiler_time }}"/>
                            </div>
                            <div class="mb-4">
                                <label for="heater_gas"
                                       class="block text-gray-700 text-sm font-bold mb-2">{{ __('Heater Gas:') }}</label>
                                <input type="text" name="heater_gas" id="heater_gas"
                                       class="form-input rounded-md shadow-sm mt-1 block w-full"
                                       value="{{ $selfSpending->heater_gas }}"/>
                            </div>
                            <div class="mb-4">
                                <label for="boiler_gas"
                                       class="block text-gray-700 text-sm font-bold mb-2">{{ __('Boiler Gas:') }}</label>
                                <input type="text" name="boiler_gas" id="boiler_gas"
                                       class="form-input rounded-md shadow-sm mt-1 block w-full"
                                       value="{{ $selfSpending->boiler_gas }}"/>
                            </div>
                            <div>
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
