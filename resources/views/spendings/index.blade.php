<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Spendings') }}
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

                    <form id="filter-form" action="{{ route('spendings.index') }}" method="GET">
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

                            <label for="station"
                                   class="w-max text-base font-semibold text-gray-800 bg-gray-200 py-2 px-3 rounded-l-md border border-gray-300 flex items-center ml-4">Станція</label>
                            <select id="station" name="user_station_id"
                                    class="block w-36 py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base"
                                    onchange="document.getElementById('filter-form').submit()">
                                <option value="">Всі станції</option>
                                @foreach ($stations as $station)
                                    <option
                                        value="{{ $station->id }}" {{ request('user_station_id') == $station->id ? 'selected' : '' }}>{{ $station->label }}</option>
                                @endforeach
                            </select>
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
                                $columns = ['created_at' => __('Created At'), 'station_label' => __('Station'), 'user_name' => __('User'), 'gas' => __('Gas'), 'odorant' => __('Odorant')];
                            @endphp
                            @foreach ($columns as $column => $label)
                                <th scope="col"
                                    class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @if ($column == 'station_label' || $column == 'user_name')
                                        {{ $label }}
                                    @else
                                        <a href="{{ route('spendings.index', ['sort' => $column, 'direction' => request('sort') === $column && request('direction') === 'asc' ? 'desc' : 'asc', 'days' => request('days', 1), 'user_station_id' => request('user_station_id')]) }}"
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
                                    @endif
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
                        @foreach ($spendings as $index => $spending)
                            @php
                                $created_at = \Carbon\Carbon::parse($spending->created_at);
                                $current_date = \Carbon\Carbon::now();
                                $diff_in_days = $created_at->diffInDays($current_date);
                            @endphp
                            <tr class="{{ $index % 2 === 0 ? 'bg-gray-100' : 'bg-white' }}">
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ \Carbon\Carbon::parse($spending->created_at)->format('d.m.y H:i') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $spending->station->label ?? 'N/A' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $spending->user->name }} {{ $spending->user->surname }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $spending->gas }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $spending->odorant }}</td>
                                <td class="px-2 py-2 whitespace-nowrap border border-gray-200 text-center">
                                    <div class="inline-flex">
                                        @if ($diff_in_days <= 3)
                                            @can('update records')
                                                <a href="{{ route('spendings.edit', $spending->id) }}"
                                                   class="px-3 py-1 text-sm font-medium leading-5 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                                                    ✎
                                                </a>
                                            @endcan
                                            @can('delete records')
                                                <form action="{{ route('spendings.destroy', $spending->id) }}"
                                                      method="POST"
                                                      onsubmit="return confirmDelete();"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1 ml-2 text-sm font-medium leading-5 text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:bg-red-600">
                                                        &#128465;
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $spendings->appends(['sort' => request('sort'), 'direction' => request('direction'), 'days' => request('days'), 'user_station_id' => request('user_station_id')])->links() }}
                    </div>

                    @can('create records')
                        <div class="mb-4">
                            <a href="{{ route('spendings.create') }}"
                               class="px-4 py-2 text-sm font-medium leading-5 text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:bg-green-600">
                                {{ __('Add Spending') }}
                            </a>
                        </div>
                    @endcan

                    @can('create reports')
                        <form id="report-form" action="{{ route('spendings.generateReport') }}" method="POST">
                            @csrf
                            <input type="hidden" name="days" value="{{ request('days', 1) }}">
                            <input type="hidden" name="user_station_id" value="{{ request('user_station_id') }}">
                            <div class="flex items-center mb-4">
                                <button type="submit"
                                        class="px-4 py-2 text-lg leading-6 text-white bg-amber-500 rounded-md hover:bg-amber-600 focus:outline-none focus:bg-amber-600">
                                    {{ __('Generate report') }}
                                </button>
                            </div>
                        </form>
                    @endcan

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
