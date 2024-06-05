<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('admin.users.index') }}" class="hover:text-blue-700">{{ __('Users') }}</a>
            / {{ __('Trashed Users') }}
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

                    <a href="{{ route('admin.users.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                        {{ __('View Active Users') }}
                    </a>

                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                        <thead>
                        <tr>
                            @php
                                $columns = ['name' => __('Name'), 'email' => __('Email'), 'station' => __('Station'), 'roles' => __('Roles')];
                                $currentSort = request('sort', 'name');
                                $currentDirection = request('direction', 'asc');
                            @endphp
                            @foreach ($columns as $column => $label)
                                <th scope="col" class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @if ($column !== 'station' && $column !== 'roles') <!-- Отключение сортировки для 'station' и 'roles' -->
                                    <a href="{{ route('admin.users.trashed', ['sort' => $column, 'direction' => $currentSort == $column && $currentDirection == 'asc' ? 'desc' : 'asc']) }}">
                                        {{ $label }}
                                        @if ($currentSort == $column)
                                            @if ($currentDirection == 'asc')
                                                &#9650; <!-- Up arrow -->
                                            @else
                                                &#9660; <!-- Down arrow -->
                                            @endif
                                        @endif
                                    </a>
                                    @else
                                        {{ $label }}
                                    @endif
                                </th>
                            @endforeach
                            <th scope="col" class="px-2 py-2 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 150px;">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($users as $index => $user)
                            <tr class="{{ $index % 2 === 0 ? 'bg-gray-100' : 'bg-white' }}">
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $user->name }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $user->email }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $user->station->label ?? 'N/A' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                                <td class="px-2 py-2 whitespace-nowrap border border-gray-200 text-center">
                                    <div class="inline-flex">
                                        <a href="{{ route('admin.users.restore', $user->id) }}" class="px-3 py-1 text-sm font-medium leading-5 text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:bg-green-600">
                                            {{ __('Restore') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
