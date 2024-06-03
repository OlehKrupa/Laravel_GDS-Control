<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Logs') }}
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

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                            <thead>
                            <tr>
                                <th scope="col"
                                    class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('ID') }}
                                </th>
                                <th scope="col"
                                    class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('User') }}
                                </th>
                                <th scope="col"
                                    class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Action') }}
                                </th>
                                <th scope="col"
                                    class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Table') }}
                                </th>
                                <th scope="col"
                                    class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Changes') }}
                                </th>
                                <th scope="col"
                                    class="px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Date') }}
                                </th>
                                <th scope="col"
                                    class="px-2 py-2 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    style="width: 150px;">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($logs as $index => $log)
                                <tr class="{{ $index % 2 === 0 ? 'bg-gray-100' :'bg-white' }}">
                                    <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $log->id }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $log->user->name }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $log->action }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $log->table_name }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap border border-gray-200">
                                        @php
                                            $oldData = json_decode($log->old_data, true);
                                            $newData = json_decode($log->new_data, true);
                                        @endphp
                                        @if($oldData && $newData)
                                            @php
                                                $changes = array_diff_assoc($newData, $oldData);
                                            @endphp
                                            @foreach ($changes as $field => $newValue)
                                                @if (is_array($newValue))
                                                    <p><strong>{{ $field }}:</strong> {{ implode(', ', $newValue) }}</p>
                                                @elseif ($field === 'updated_at')
                                                    <p><strong>{{ $field }}:</strong> {{ \Carbon\Carbon::parse($newValue)->format('Y-m-d H:i:s') }}</p>
                                                @else
                                                    <p><strong>{{ $field }}:</strong> {{ $oldData[$field]?? 'null' }} -> {{ $newValue }}</p>
                                                @endif
                                            @endforeach
                                        @elseif($oldData &&!$newData)
                                            <p><strong>{{ __('Deleted') }}</strong></p>
                                        @else
                                            <p><strong>{{ __('No changes') }}</strong></p>
                                        @endif
                                    </td>

                                    <td class="px-3 py-2 whitespace-nowrap border border-gray-200">{{ $log->created_at }}</td>
                                    <td class="px-2 py-2 whitespace-nowrap border border-gray-200 text-center">
                                        <form action="{{ url('admin/undo',[$log->table_name, $log->id]) }}"
                                              method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 text-sm font-medium leading-5 text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:bg-red-600">
                                                {{ __('Undo') }}
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.delete', $log->id) }}" method="POST"
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 text-sm font-medium leading-5 text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:bg-red-600">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>

                        <div class="flex justify-start mb-4">
                            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Return to Dashboard') }}
                            </a>
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
