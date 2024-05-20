<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add gassiness measurements') }}
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

                    <form method="POST" action="{{ route('gassiness.store') }}">
                        @csrf

                        <div class="flex items-center mb-4">
                            <label for="MPR" class="w-40 text-lg font-semibold text-gray-800">MPR:</label>
                            <input type="text" name="MPR" id="MPR"
                                   class="w-64 pl-2 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="measurements[]"
                                   class="w-40 text-lg font-semibold text-gray-800">Measurements:</label>
                            <input type="text" name="measurements[]" id="measurements_1"
                                   class="w-20 pl-2 py-2 border border-gray-300 rounded-md">
                            <input type="text" name="measurements[]" id="measurements_2"
                                   class="w-20 pl-2 py-2 border border-gray-300 rounded-md" style="display: none;">
                            @for ($i = 3; $i <= 10; $i++)
                                <input type="text" name="measurements[]" id="measurements_{{ $i }}"
                                       class="w-20 pl-2 py-2 border border-gray-300 rounded-md" style="display: none;">
                            @endfor
                        </div>

                        <script>
                            let measurements = document.querySelectorAll('input[name="measurements[]"]');
                            measurements.forEach((input, index) => {
                                input.addEventListener('input', () => {
                                    if (index < 9) {
                                        measurements[index + 1].style.display = 'block';
                                    }
                                });
                            });
                        </script>

                        <div class="flex items-center mb-4">
                            <label for="device" class="w-40 text-lg font-semibold text-gray-800">Device:</label>
                            <input type="text" name="device" id="device"
                                   class="w-64 pl-2 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="factory_number" class="w-40 text-lg font-semibold text-gray-800">Factory
                                Number:</label>
                            <input type="text" name="factory_number" id="factory_number"
                                   class="w-64 pl-2 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="px-4 py-2 text-lg font-semibold text-gray-900 bg-blue-500 rounded-md hover:bg-blue-600">
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
