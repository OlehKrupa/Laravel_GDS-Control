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

                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                        <thead>
                        <tr>
                            @php
                                $columns = [
                                    'operational_switching' => __('Operational Switching'),
                                    'received_orders' => __('Received Orders'),
                                    'completed_works' => __('Completed Works'),
                                    'visits_by_outsiders' => __('Visits by Outsiders'),
                                    'inspection_of_pressure_tanks' => __('Inspection of Pressure Tanks'),
                                ];
                                $currentSort = request('sort', 'operational_switching');
                                $currentDirection = request('direction', 'asc');
                            @endphp
                            @foreach ($columns as $column => $label)
                                <th scope="col" class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('notes.index', ['sort' => $column, 'direction' => $currentSort == $column && $currentDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                            <th scope="col" class="px-2 py-2 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 150px;">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($notes as $note)
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $note->operational_switching }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $note->received_orders }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $note->completed_works }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $note->visits_by_outsiders }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $note->inspection_of_pressure_tanks }}</td>
                                <td class="px-2 py-2 whitespace-nowrap border border-gray-200 text-center">
                                    <div class="inline-flex">
                                        <a href="{{ route('notes.edit', $note->id) }}" class="px-3 py-1 text-sm font-medium leading-5 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                                            {{ __('Edit') }}
                                        </a>
                                        <form action="{{ route('notes.destroy', $note->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 ml-2 text-sm font-medium leading-5 text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:bg-red-600">
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
                        {{ $notes->links() }}
                    </div>

                    <div class="mb-4">
                        <a href="{{ route('notes.create') }}"
                           class="px-4 py-2 text-sm font-medium leading-5 text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:bg-green-600 mr-2">
                            {{ __('Create New Note') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>