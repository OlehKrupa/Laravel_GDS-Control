<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
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

                    <a href="{{ route('admin.users.create') }}"
                       class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                        {{ __('Create User') }}
                    </a>
                    <a href="{{ route('admin.users.trashed') }}"
                       class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                        {{ __('View Trashed Users') }}
                    </a>

                    <!-- Форма фильтрации по станции -->
                    <form action="{{ route('admin.users.index') }}" method="GET">
                        <input type="hidden" name="sort" value="{{ request('sort', 'name') }}">
                        <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">

                        <div class="flex items-center mb-4">
                            <label for="station"
                                   class="w-max text-base font-semibold text-gray-800 bg-gray-200 py-2 px-3 rounded-l-md border border-gray-300 flex items-center">Станція</label>
                            <select id="station" name="station_id"
                                    class="block w-64 py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base"
                                    onchange="this.form.submit()">
                                <option value="">{{ __('All Stations') }}</option>
                                @foreach ($stations as $station)
                                    <option
                                        value="{{ $station->id }}" {{ request('station_id') == $station->id ? 'selected' : '' }}>
                                        {{ $station->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                        <thead>
                        <tr>
                            @php
                                $columns = ['name' => __('Name'),'surname' => __('Surname'), 'email' => __('Email')];
                                $currentSort = request('sort', 'name');
                                $currentDirection = request('direction', 'asc');
                            @endphp
                            @foreach ($columns as $column => $label)
                                <th scope="col"
                                    class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('admin.users.index', ['sort' => $column, 'direction' => $currentSort == $column && $currentDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                                class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Station') }}
                            </th>
                            <th scope="col"
                                class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Roles') }}
                            </th>
                            <th scope="col"
                                class="px-2 py-2 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                style="width: 150px;">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($users as $index => $user)
                            <tr class="{{ $index % 2 === 0 ? 'bg-gray-100' : 'bg-white' }}">
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $user->name }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $user->surname }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $user->email }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $user->station->label ?? 'N/A' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                                <td class="px-2 py-2 whitespace-nowrap border border-gray-200 text-center">
                                    <div class="inline-flex">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="px-3 py-1 text-sm font-medium leading-5 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                                            {{ __('Edit') }}
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
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
                        {{ $users->links() }}
                    </div>
                    <div class="flex justify-start mb-4">
                        <a href="{{ route('dashboard') }}"
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Return to Dashboard') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
