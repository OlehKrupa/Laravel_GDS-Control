<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('spendings.index') }}" class="hover:text-blue-700">{{ __('Spendings') }}</a>
            / {{ __('Add Spending') }}
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
                        <form method="POST" action="{{ route('spendings.store') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="gas"
                                       class="block text-gray-700 text-sm font-bold mb-2">{{ __('Gas:') }}</label>
                                <input type="text" name="gas" id="gas"
                                       class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            </div>
                            <div class="mb-4">
                                <label for="odorant"
                                       class="block text-gray-700 text-sm font-bold mb-2">{{ __('Odorant:') }}</label>
                                <input type="text" name="odorant" id="odorant"
                                       class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            </div>
                            <div>
                                <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('Create record') }}
                                </button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
