<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('gassiness.index') }}" class="hover:text-blue-700">{{ __('Gassiness') }}</a>
            / {{ __('edit_gassiness') }}
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

                    <form method="POST" action="{{ route('gassiness.update', $gassiness->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="flex items-center mb-4">
                            <label for="MPR"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('MPR:') }}</label>
                            <input type="text" name="MPR" id="MPR"
                                   class="w-64 py-2 px-4 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ $gassiness->MPR }}"/>
                        </div>

                        <div class="mb-4">
                            <label for="measurements"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-md">{{ __('Measurements:') }}</label>
                            @foreach ($gassiness->measurements as $key => $measurement)
                                <input type="text" name="measurements[]" id="measurements_{{ $key }}"
                                       class="w-20 py-2 px-4 border border-gray-300 rounded-md m-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ $measurement }}"/>
                            @endforeach
                            @for ($i = count($gassiness->measurements); $i < 10; $i++)
                                <input type="text" name="measurements[]" id="measurements_{{ $i }}"
                                       class="w-20 py-2 px-4 border border-gray-300 rounded-md m-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       style="display: none;"/>
                            @endfor
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                let measurements = document.querySelectorAll('input[name="measurements[]"]');
                                measurements.forEach((input, index) => {
                                    input.addEventListener('input', () => {
                                        if (index < measurements.length - 1 && input.value.trim() !== '') {
                                            measurements[index + 1].style.display = 'inline-block';
                                        }
                                    });
                                });
                            });
                        </script>

                        <div class="flex items-center mb-4">
                            <label for="device"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Device:') }}</label>
                            <input type="text" name="device" id="device"
                                   class="w-64 py-2 px-4 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ $gassiness->device }}"/>
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="factory_number"
                                   class="w-64 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Factory Number:') }}</label>
                            <input type="text" name="factory_number" id="factory_number"
                                   class="w-64 py-2 px-4 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ $gassiness->factory_number }}"/>
                        </div>

                        <div>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Update record') }}
                            </button>
                            <button type="button" onclick="window.location.href='{{ route('gassiness.index') }}'"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Back') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
