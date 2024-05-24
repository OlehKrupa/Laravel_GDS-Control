<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                        <thead>
                        <tr>
                            @php
                                $columns = ['label' => __('Label'), 'city' => __('City'), 'region' => __('Region'), 'type' => __('Type')];
                                $currentSort = request('sort', 'label');
                                $currentDirection = request('direction', 'asc');
                            @endphp
                            @foreach ($columns as $column => $label)
                                <th scope="col"
                                    class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('stations.index', ['sort' => $column, 'direction' => $currentSort == $column && $currentDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                        @foreach ($stations as $index => $station)
                            <tr class="{{ $index % 2 === 0 ? 'bg-gray-100' : 'bg-white' }}">
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $station->label }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $station->city }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $station->region }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $station->type }}</td>
                                <td class="px-2 py-2 whitespace-nowrap border border-gray-200 text-center">
                                    <div class="inline-flex">
                                        <a href="{{ route('stations.edit', $station->id) }}"
                                           class="px-3 py-1 text-sm font-medium leading-5 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                                            {{ __('Edit') }}
                                        </a>
                                        <form action="{{ route('stations.destroy', $station->id) }}" method="POST"
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 ml-2 text-sm font-medium leading-5 text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:bg-red-600">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $stations->links() }}
                    </div>

                    <div class="mb-4">
                        <a href="{{ route('stations.create') }}"
                           class="px-4 py-2 text-sm font-medium leading-5 text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:bg-green-600 mr-2">
                            {{ __('Add Station') }}
                        </a>
                    </div>


                    <form action="{{ route('station.generate.report') }}" method="post">
                        <div class="flex items-center mb-4">
                            @csrf
                            <label class="w-max text-base font-semibold text-gray-800 bg-gray-200 py-2 px-3 rounded-l-md border border-gray-300 flex items-center"
                                   for="report_type">{{__('select_report_type:')}}</label>
                            <select
                                class="block w-max py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base"
                                id="report_type" name="report_type">
                                <option value="stations_by_city">{{__('stations_by_city')}}</option>
                                <option value="stations_by_region">{{__('stations_by_region')}}</option>
                                <option value="stations_by_type">{{__('stations_by_type')}}</option>
                            </select>
                            <button type="submit" class="px-4 py-2 ml-2 text-lg leading-6 text-white bg-amber-500 rounded-md hover:bg-amber-600 focus:outline-none focus:bg-amber-600">{{__('Generate report')}}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
