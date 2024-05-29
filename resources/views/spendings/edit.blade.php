<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('spendings.index') }}" class="hover:text-blue-700">{{ __('Spendings') }}</a>
            / {{ __('Edit Spending') }}
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
                    <form method="POST" action="{{ route('spendings.update', $spending->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="flex items-center mb-4">
                            <label for="gas"
                                   class="w-40 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Gas:') }}</label>
                            <div class="flex w-64">
                                <input type="text" name="gas" id="gas"
                                       class="w-64 py-2 px-4 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ $spending->gas }}"/>
                                <span
                                    class="inline-flex items-center px-3 text-gray-700 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md">M3</span>
                            </div>
                        </div>
                        <div class="flex items-center mb-4">
                            <label for="odorant"
                                   class="w-40 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md">{{ __('Odorant:') }}</label>
                            <div class="flex w-64">
                                <input type="text" name="odorant" id="odorant"
                                       class="w-64 py-2 px-4 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ $spending->odorant }}"/>
                                <span
                                    class="inline-flex items-center px-3 text-gray-700 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md">Kg</span>
                            </div>
                        </div>
                        <div>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Update record') }}
                            </button>
                            <button type="button" onclick="window.location.href='{{ route('spendings.index') }}'"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Back') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
