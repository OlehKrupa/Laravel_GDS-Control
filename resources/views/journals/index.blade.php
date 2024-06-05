<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Journals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form id="days-form" action="{{ route('journals.index') }}" method="GET">
                        <input type="hidden" name="sort" value="{{ request('sort', 'created_at') }}">
                        <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">

                        <div class="flex items-center mb-4">
                            <label for="days" class="w-max text-base font-semibold text-gray-800 bg-gray-200 py-2 px-3 rounded-l-md border border-gray-300 flex items-center">Кількість днів для відображення</label>
                            <select id="days" name="days" class="block w-36 py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base" onchange="document.getElementById('days-form').submit()">
                                <option value="1" {{ request('days', 1) == 1 ? 'selected' : '' }}>1 день</option>
                                <option value="3" {{ request('days', 1) == 3 ? 'selected' : '' }}>3 дні</option>
                                <option value="7" {{ request('days', 1) == 7 ? 'selected' : '' }}>7 днів</option>
                                <option value="30" {{ request('days', 1) == 30 ? 'selected' : '' }}>30 днів</option>
                            </select>

                            <label for="station" class="w-max text-base font-semibold text-gray-800 bg-gray-200 py-2 px-3 rounded-l-md border border-gray-300 flex items-center ml-4">Станція</label>
                            <select id="station" name="user_station_id" class="block w-64 py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base" onchange="document.getElementById('days-form').submit()">
                                <option value="">Всі станції</option>
                                @foreach ($stations as $station)
                                    <option value="{{ $station->id }}" {{ request('user_station_id') == $station->id ? 'selected' : '' }}>{{ $station->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                        <thead>
                        <tr>
                            @php
                                $columns = [
                                    'created_at' => __('Created At'),
                                    'pressure_in' => __('Pressure In'),
                                    'pressure_out_1' => __('Pressure Out 1'),
                                    'pressure_out_2' => __('Pressure Out 2'),
                                    'temperature_1' => __('Temperature 1'),
                                    'temperature_2' => __('Temperature 2'),
                                    'odorant_value_1' => __('Odorant Value 1'),
                                    'odorant_value_2' => __('Odorant Value 2'),
                                    'gas_heater_temperature_in' => __('Gas Heater Temperature In'),
                                    'gas_heater_temperature_out' => __('Gas Heater Temperature Out'),
                                ];
                                $currentSort = request('sort', 'created_at');
                                $currentDirection = request('direction', 'asc');
                            @endphp
                            @foreach ($columns as $column => $label)
                                <th scope="col"
                                    class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('journals.index', ['sort' => $column, 'direction' => $currentSort == $column && $currentDirection == 'asc' ? 'desc' : 'asc', 'days' => request('days', 1), 'user_station_id' => request('user_station_id')]) }}">
                                        {{ $label }}
                                        @if ($currentSort == $column)
                                            @if ($currentDirection == 'asc')
                                                &#9650; <!-- Up arrow -->
                                            @else
                                                &#9660; <!-- Down arrow -->
                                            @endif
                                        @endif
                                    </a>
                                </th>
                            @endforeach
                            <th scope="col"
                                class="px-2 py-2 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                style="width: 150px;">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($journals as $index => $journal)
                            @php
                                $created_at = \Carbon\Carbon::parse($journal->created_at);
                                $current_date = \Carbon\Carbon::now();
                                $diff_in_days = $created_at->diffInDays($current_date);
                            @endphp
                            <tr class="{{ $index % 2 === 0 ? 'bg-gray-100' : 'bg-white' }}">
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $journal->created_at }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $journal->pressure_in }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $journal->pressure_out_1 }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $journal->pressure_out_2 }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $journal->temperature_1 }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $journal->temperature_2 }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $journal->odorant_value_1 }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $journal->odorant_value_2 }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $journal->gas_heater_temperature_in }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $journal->gas_heater_temperature_out }}</td>
                                <td class="px-2 py-2 whitespace-nowrap border border-gray-200 text-center">
                                    @if ($diff_in_days <= 3)
                                        <div class="inline-flex">
                                            <a href="{{ route('journals.edit', $journal->id) }}"
                                               class="px-3 py-1 text-sm font-medium leading-5 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                                                {{ __('Edit') }}
                                            </a>
                                            <form action="{{ route('journals.destroy', $journal->id) }}" method="POST" onsubmit="return confirmDelete();"
                                                  style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1 ml-2 text-sm font-medium leading-5 text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:bg-red-600">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $journals->appends(['sort' => request('sort'), 'direction' => request('direction'), 'days' => request('days'), 'user_station_id' => request('user_station_id')])->links() }}
                    </div>

                    <div class="mb-4">
                        <a href="{{ route('journals.create') }}"
                           class="px-4 py-2 text-sm font-medium leading-5 text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:bg-green-600 mr-2">
                            {{ __('Add Journal') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete() {
            return confirm('Ви впевнені у видаленні?');
        }
    </script>
</x-app-layout>
