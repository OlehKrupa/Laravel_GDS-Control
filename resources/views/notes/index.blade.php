<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notes') }}
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

                    <form id="filter-form" action="{{ route('notes.index') }}" method="GET">
                        <input type="hidden" name="sort" value="{{ request('sort', 'created_at') }}">
                        <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
                        <div class="flex items-center mb-4">
                            <label for="days"
                                   class="w-max text-base font-semibold text-gray-800 bg-gray-200 py-2 px-3 rounded-l-md border border-gray-300 flex items-center">Кількість
                                днів для відображення</label>
                            <select id="days" name="days"
                                    class="block w-36 py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base"
                                    onchange="document.getElementById('filter-form').submit()">
                                <option value="1" {{ request('days', 1) == 1 ? 'selected' : '' }}>1 день</option>
                                <option value="3" {{ request('days', 1) == 3 ? 'selected' : '' }}>3 дні</option>
                                <option value="7" {{ request('days', 1) == 7 ? 'selected' : '' }}>7 днів</option>
                                <option value="30" {{ request('days', 1) == 30 ? 'selected' : '' }}>30 днів</option>
                            </select>

                            @can('use stations')
                                <label for="station"
                                       class="w-max text-base font-semibold text-gray-800 bg-gray-200 py-2 px-3 rounded-l-md border border-gray-300 flex items-center ml-4">Станція</label>
                                <select id="station" name="user_station_id"
                                        class="block w-64 py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base"
                                        onchange="document.getElementById('filter-form').submit()">
                                    <option value="">Всі станції</option>
                                    @foreach ($stations as $station)
                                        <option
                                            value="{{ $station->id }}" {{ request('user_station_id') == $station->id ? 'selected' : '' }}>{{ $station->label }}</option>
                                    @endforeach
                                </select>
                            @endcan
                        </div>
                    </form>

                    @php
                        $currentSort = request('sort', 'created_at');
                        $currentDirection = request('direction', 'asc');
                    @endphp

                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                        <thead>
                        <tr>
                            @php
                                $columns = [
                                    'created_at' => __('Date'),
                                    'operational_switching' => __('Operational Switching'),
                                    'received_orders' => __('Received Orders'),
                                    'completed_works' => __('Completed Works'),
                                    'visits_by_outsiders' => __('Visits by Outsiders'),
                                    'inspection_of_pressure_tanks' => __('Inspection of Pressure Tanks')
                                ];
                            @endphp
                            @foreach ($columns as $column => $label)
                                <th scope="col"
                                    class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('notes.index', ['sort' => $column, 'direction' => request('sort') === $column && request('direction') === 'asc' ? 'desc' : 'asc', 'days' => request('days', 1), 'user_station_id' => request('user_station_id')]) }}"
                                       class="flex items-center space-x-1">
                                        <span>{{ $label }}</span>
                                        @if (request('sort') === $column)
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
                        @foreach ($notes as $note)
                            @php
                                $created_at = \Carbon\Carbon::parse($note->created_at);
                                $current_date = \Carbon\Carbon::now();
                                $diff_in_days = $created_at->diffInDays($current_date);
                            @endphp
                            <tr class="{{ $loop->index % 2 === 0 ? 'bg-gray-100' : 'bg-white' }}">
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $note->created_at }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $note->operational_switching }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $note->received_orders }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $note->completed_works }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $note->visits_by_outsiders }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $note->inspection_of_pressure_tanks }}</td>
                                <td class="px-2 py-2 whitespace-nowrap border border-gray-200 text-center">
                                    @if ($diff_in_days <= 3)
                                        <div class="inline-flex">
                                            @can('update records')
                                                <a href="{{ route('notes.edit', $note->id) }}"
                                                   class="px-3 py-1 text-sm font-medium leading-5 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                                                    {{ __('Edit') }}
                                                </a>
                                            @endcan
                                            @can('delete records')
                                                <form action="{{ route('notes.destroy', $note->id) }}" method="POST"
                                                      onsubmit="return confirmDelete();"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1 ml-2 text-sm font-medium leading-5 text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:bg-red-600">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $notes->appends(['sort' => request('sort'), 'direction' => request('direction'), 'days' => request('days'), 'user_station_id' => request('user_station_id')])->links() }}
                    </div>

                    @can('create records')
                        <div class="mb-4">
                            <a href="{{ route('notes.create') }}"
                               class="px-4 py-2 text-sm font-medium leading-5 text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:bg-green-600 mr-2">
                                {{ __('Add Note') }}
                            </a>
                        </div>
                    @endcan

                    @can('create reports')
                        <form action="{{ route('notes.generateReport') }}" method="post">
                            @csrf
                            <input type="hidden" name="days" value="{{ request('days', 1) }}">
                            <input type="hidden" name="user_station_id" value="{{ request('user_station_id') }}">
                            <div class="flex items-center mb-4">
                                <label for="parameter"
                                       class="w-max text-base font-semibold text-gray-800 bg-gray-200 py-2 px-3 rounded-l-md border border-gray-300 flex items-center">Звіт:</label>
                                <select id="parameter" name="parameter"
                                        class="block w-max py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base">
                                    <option value="all">Всі параметри</option>
                                    <option value="operational_switching">Оперативне перемикання</option>
                                    <option value="received_orders">Отримані накази</option>
                                    <option value="completed_works">Завершені роботи</option>
                                    <option value="visits_by_outsiders">Візити сторонніх осіб</option>
                                    <option value="inspection_of_pressure_tanks">Інспекція резервуарів тиску</option>
                                </select>
                                <button type="submit"
                                        class="px-4 py-2 ml-2 text-lg leading-6 text-white bg-amber-500 rounded-md hover:bg-amber-600 focus:outline-none focus:bg-amber-600">{{ __('Generate report') }}</button>
                            </div>
                        </form>
                    @endcan

                </div>
            </div>
        </div>
    </div>
    <script>
        function submitFilterForm() {
            document.getElementById('filter-form').submit();
        }
    </script>
</x-app-layout>
