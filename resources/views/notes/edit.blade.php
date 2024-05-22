<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Note') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="mb-4 text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-4 text-red-600">
                            <ul>
                                @foreach ($errors->unique() as $error)
                                    <li>{{ __($error) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('notes.update', $note->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="flex items-center mb-4">
                            <label for="operational_switching"
                                   class="w-1/4 text-lg font-semibold text-gray-800">{{ __('Operational Switching') }}:</label>
                            <input type="text" name="operational_switching" id="operational_switching"
                                   value="{{ $note->operational_switching }}"
                                   class="w-64 pl-2 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="received_orders"
                                   class="w-1/4 text-lg font-semibold text-gray-800">{{ __('Received Orders') }}:</label>
                            <input type="text" name="received_orders" id="received_orders"
                                   value="{{ $note->received_orders }}"
                                   class="w-64 pl-2 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="completed_works"
                                   class="w-1/4 text-lg font-semibold text-gray-800">{{ __('Completed Works') }}:</label>
                            <input type="text" name="completed_works" id="completed_works"
                                   value="{{ $note->completed_works }}"
                                   class="w-64 pl-2 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="visits_by_outsiders"
                                   class="w-1/4 text-lg font-semibold text-gray-800">{{ __('Visits by Outsiders') }}:</label>
                            <input type="text" name="visits_by_outsiders" id="visits_by_outsiders"
                                   value="{{ $note->visits_by_outsiders }}"
                                   class="w-64 pl-2 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div class="flex items-center mb-4">
                            <label for="inspection_of_pressure_tanks"
                                   class="w-1/4 text-lg font-semibold text-gray-800">{{ __('Inspection of Pressure Tanks') }}:</label>
                            <input type="text" name="inspection_of_pressure_tanks" id="inspection_of_pressure_tanks"
                                   value="{{ $note->inspection_of_pressure_tanks }}"
                                   class="w-64 pl-2 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ __('Update Note') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
