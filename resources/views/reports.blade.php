<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('reports.generate') }}" method="post">
                        @csrf
                        <label for="report_type">Select report type:</label>
                        <select id="report_type" name="report_type">
                            <option value="stations_by_city">Stations by city</option>
                            <option value="stations_by_region">Stations by region</option>
                            <option value="stations_by_type">Stations by type</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Generate report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
