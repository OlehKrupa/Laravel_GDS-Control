<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('selfSpendings.index') }}" class="hover:text-blue-700">{{ __('Self Spendings') }}</a>
            / {{ __('Add Self Spending') }}
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
                        <form method="POST" action="{{ route('selfSpendings.store') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="heater_time"
                                       class="block text-gray-700 text-sm font-bold mb-2">{{ __('Heater Time:') }}</label>
                                <input type="text" name="heater_time" id="heater_time"
                                       class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            </div>
                            <div class="mb-4">
                                <label for="boiler_time"
                                       class="block text-gray-700 text-sm font-bold mb-2">{{ __('Boiler Time:') }}</label>
                                <input type="text" name="boiler_time" id="boiler_time"
                                       class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            </div>
                            <div class="mb-4">
                                <label for="heater_gas"
                                       class="block text-gray-700 text-sm font-bold mb-2">{{ __('Heater Gas:') }}</label>
                                <input type="text" name="heater_gas" id="heater_gas"
                                       class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            </div>
                            <div class="mb-4">
                                <label for="boiler_gas"
                                       class="block text-gray-700 text-sm font-bold mb-2">{{ __('Boiler Gas:') }}</label>
                                <input type="text" name="boiler_gas" id="boiler_gas"
                                       class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            </div>
                            <div>
                                <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('Create record') }}
                                </button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
