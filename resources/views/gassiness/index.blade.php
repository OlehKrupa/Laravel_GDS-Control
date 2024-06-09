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
                    <form id="filter-form" action="{{ route('gassiness.index') }}" method="GET">
                        <div class="flex items-center mb-4">
                            <label for="days" class="w-max text-base font-semibold text-gray-800 bg-gray-200 py-2 px-3 rounded-l-md border border-gray-300 flex items-center">Кількість днів для відображення</label>
                            <select id="days" name="days" class="block w-36 py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base" onchange="document.getElementById('filter-form').submit()">
                                <option value="1" {{ request('days', 1) == 1 ? 'selected' : '' }}>1 день</option>
                                <option value="3" {{ request('days', 1) == 3 ? 'selected' : '' }}>3 дні</option>
                                <option value="7" {{ request('days', 1) == 7 ? 'selected' : '' }}>7 днів</option>
                                <option value="30" {{ request('days', 1) == 30 ? 'selected' : '' }}>30 днів</option>
                            </select>

                            <label for="station" class="w-max text-base font-semibold text-gray-800 bg-gray-200 py-2 px-3 rounded-l-md border border-gray-300 flex items-center ml-4">Станція</label>
                            <select id="station" name="user_station_id" class="block w-36 py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base" onchange="document.getElementById('filter-form').submit()">
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
                                $columns = ['created_at' => __('Created At'), 'MPR' => __('MPR'), 'device' => __('Device'), 'factory_number' => __('Factory Number')];
                                $currentSort = request('sort', 'created_at');
                                $currentDirection = request('direction', 'asc');
                            @endphp
                            @foreach ($columns as $column => $label)
                                <th scope="col" class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('gassiness.index', ['sort' => $column, 'direction' => $currentSort == $column && $currentDirection == 'asc' ? 'desc' : 'asc', 'days' => request('days', 1), 'user_station_id' => request('user_station_id')]) }}">
                                        {{ $label }}
                                        @if ($currentSort == $column)
                                            @if ($currentDirection == 'asc')
                                                &#9650;
                                            @else
                                                &#9660;
                                            @endif
                                        @endif
                                    </a>
                                </th>
                            @endforeach
                            <th scope="col" class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Measurements') }}</th>
                            <th scope="col" class="px-2 py-2 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 150px;">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($gassinesses as $index => $gassiness)
                            <tr class="{{ $index % 2 === 0 ? 'bg-gray-100' : 'bg-white' }}">
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $gassiness->created_at }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $gassiness->MPR }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $gassiness->device }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $gassiness->factory_number }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200 flex flex-wrap">
                                    @foreach ($gassiness->measurements as $measurement)
                                        @if ($measurement !== null)
                                            <span class="bg-gray-100 px-2 py-1 m-1 rounded border border-gray-300">{{ $measurement }}</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap border border-gray-200 text-center">
                                    <div class="inline-flex">
                                        @if ($gassiness->created_at->gt(now()->subDays(3)))
                                            <a href="{{ route('gassiness.edit', $gassiness->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">{{ __('Edit') }}</a>
                                        @endif
                                        <form action="{{ route('gassiness.destroy', $gassiness->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this item?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $gassinesses->links() }}
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('gassiness.generateReport') }}" method="POST">
                            @csrf
                            <input type="hidden" name="days" value="{{ request('days', 1) }}">
                            <input type="hidden" name="user_station_id" value="{{ request('user_station_id') }}">
                            <button type="submit" class="px-4 py-2 text-lg leading-6 text-white bg-amber-500 rounded-md hover:bg-amber-600 focus:outline-none focus:bg-amber-600">{{ __('Generate Report') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
