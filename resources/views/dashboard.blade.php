<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form id="filterForm">
                        <div class="flex flex-wrap items-center mb-4">
                            <div class="flex items-center mb-4 mr-4 md:w-auto w-full">
                                <label
                                    class="w-40 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md"
                                    for="station">
                                    {{ __('Station') }}
                                </label>
                                <select id="station" name="station_id"
                                        class="block w-36 py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base">
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}">{{ $station->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center mb-4 mr-4 md:w-auto w-full">
                                <label
                                    class="w-20 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md"
                                    for="days">
                                    {{ __('Days') }}
                                </label>
                                <select id="days" name="days"
                                        class="block w-36 py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base">
                                    <option value="1">1 день</option>
                                    <option value="3">3 дні</option>
                                    <option value="7">7 днів</option>
                                    <option value="30">30 днів</option>
                                </select>
                            </div>
                            <div class="flex items-center mb-4 mr-4 md:w-auto w-full">
                                <label
                                    class="w-20 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md"
                                    for="model">
                                    {{ __('Record') }}
                                </label>
                                <select id="model" name="model"
                                        class="block w-36 py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base">
                                    @foreach ($models as $id => $name)
                                        <option value="{{ $id }}">{{ __($name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center mb-4 mr-4 md:w-auto w-full">
                                <label
                                    class="w-20 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md"
                                    for="field">
                                    {{ __('Field') }}
                                </label>
                                <select id="field" name="field"
                                        class="block w-40 py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base">
                                    <!-- Поля будут загружаться динамически -->
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="flex items-center flex-wrap mb-4">
                        <label class="w-40 text-lg font-semibold text-gray-800 bg-gray-200 py-2 px-4 rounded-l-md"
                               for="chartType">
                            {{ __('Chart Type') }}
                        </label>
                        <select id="chartType" name="chartType"
                                class="block w-36 py-2 px-4 border border-gray-300 bg-white rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base">
                            <option value="line">Лінія</option>
                            <option value="column">Стовпчик</option>
                            <option value="bar">Смуга</option>
                            <option value="area">Область</option>
                            <option value="spline">Сплайн</option>
                        </select>

                        <button id="forecastButton"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold ml-4 py-2 px-4 rounded">
                            Зробити прогноз
                        </button>
                    </div>


                    <div id="chart" style="width: 100%; height: 400px;"></div>

                    @can('logs')
                        <div class="bg-orange-100 p-4 rounded-md mt-4">
                            <h3 class="text-lg font-bold">{{ __('Admin Section') }}</h3>
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <a href="{{ route('admin.logs') }}">{{ __('Logging') }}</a>
                            </button>
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <a href="{{ route('admin.users.index') }}">{{ __('Personnel_accounting') }}</a>
                            </button>
                        </div>
                    @endcan

                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://code.highcharts.com/highcharts.js"></script>
                    <script src="https://code.highcharts.com/modules/exporting.js"></script>
                    <script src="https://code.highcharts.com/modules/export-data.js"></script>
                    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

                    <script>
                        $(document).ready(function () {
                            // Загружаем данные полей
                            function loadFields(model) {
                                $.get('/get-fields', {model: model}, function (data) {
                                    $('#field').empty();
                                    data.fields.forEach(function (field) {
                                        $('#field').append(new Option(field.translated, field.original));
                                    });
                                });
                            }

                            loadFields($('#model').val());

                            $('#model').on('change', function () {
                                loadFields($(this).val());
                            });

                            // Загружаем данные графика
                            function loadChartData() {
                                var formData = $('#filterForm').serialize();
                                var fieldName = $('#field option:selected').text();
                                var fieldKey = $('#field').val();
                                $.get('/chart-data', formData, function (data) {
                                    var chartType = $('#chartType').val();
                                    Highcharts.chart('chart', {
                                        chart: {
                                            type: chartType
                                        },
                                        title: {
                                            text: null
                                        },
                                        xAxis: {
                                            categories: Object.keys(data),
                                            title: {
                                                text: 'Дата'
                                            }
                                        },
                                        yAxis: {
                                            title: {
                                                text: 'Значення'
                                            }
                                        },
                                        series: [{
                                            data: Object.values(data),
                                            name: fieldName,
                                        }],
                                        lang: {
                                            loading: 'Завантаження...',
                                            months: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'],
                                            weekdays: ['Неділя', 'Понеділок', 'Вівторок', 'Середа', 'Четвер', 'П’ятниця', 'Субота'],
                                            shortMonths: ['Січ', 'Лют', 'Бер', 'Кві', 'Тра', 'Чер', 'Лип', 'Сер', 'Вер', 'Жов', 'Лис', 'Гру'],
                                            exportButtonTitle: "Експортувати",
                                            printButtonTitle: "Друк",
                                            rangeSelectorFrom: "З",
                                            rangeSelectorTo: "По",
                                            rangeSelectorZoom: "Період",
                                            downloadPNG: 'Завантажити PNG',
                                            downloadJPEG: 'Завантажити JPEG',
                                            downloadPDF: 'Завантажити PDF',
                                            downloadSVG: 'Завантажити SVG',
                                            resetZoom: "Скинути масштаб",
                                            resetZoomTitle: "Скинути масштаб до 1:1",
                                            thousandsSep: " ",
                                            decimalPoint: ','
                                        },
                                        exporting: {
                                            buttons: {
                                                contextButton: {
                                                    menuItems: [
                                                        'downloadPNG',
                                                        'downloadJPEG',
                                                        'downloadPDF',
                                                        'downloadSVG'
                                                    ]
                                                }
                                            }
                                        }
                                    });
                                });
                            }

                            $('#station, #days, #model, #field, #chartType').on('change', loadChartData);

                            $('#forecastButton').on('click', function () {
                                var formData = $('#filterForm').serialize();
                                var fieldName = $('#field option:selected').text();
                                var fieldKey = $('#field').val();
                                $.get('/forecast-data', formData, function (data) {
                                    var chart = Highcharts.chart('chart', {
                                        chart: {
                                            type: $('#chartType').val()
                                        },
                                        title: {
                                            text: null
                                        },
                                        xAxis: {
                                            categories: Object.keys(data.actualData),
                                            title: {
                                                text: 'Дата'
                                            }
                                        },
                                        yAxis: {
                                            title: {
                                                text: 'Значення'
                                            }
                                        },
                                        series: [{
                                            data: Object.values(data.actualData),
                                            name: fieldName,
                                            color: 'blue'
                                        }, {
                                            data: data.forecastData,
                                            name: 'Прогноз',
                                            color: 'green',
                                            dashStyle: 'Dash'
                                        }],
                                        lang: {
                                            loading: 'Завантаження...',
                                            months: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'],
                                            weekdays: ['Неділя', 'Понеділок', 'Вівторок', 'Середа', 'Четвер', 'П’ятниця', 'Субота'],
                                            shortMonths: ['Січ', 'Лют', 'Бер', 'Кві', 'Тра', 'Чер', 'Лип', 'Сер', 'Вер', 'Жов', 'Лис', 'Гру'],
                                            exportButtonTitle: "Експортувати",
                                            printButtonTitle: "Друк",
                                            rangeSelectorFrom: "З",
                                            rangeSelectorTo: "По",
                                            rangeSelectorZoom: "Період",
                                            downloadPNG: 'Завантажити PNG',
                                            downloadJPEG: 'Завантажити JPEG',
                                            downloadPDF: 'Завантажити PDF',
                                            downloadSVG: 'Завантажити SVG',
                                            resetZoom: "Скинути масштаб",
                                            resetZoomTitle: "Скинути масштаб до 1:1",
                                            thousandsSep: " ",
                                            decimalPoint: ','
                                        },
                                        exporting: {
                                            buttons: {
                                                contextButton: {
                                                    menuItems: [
                                                        'downloadPNG',
                                                        'downloadJPEG',
                                                        'downloadPDF',
                                                        'downloadSVG'
                                                    ]
                                                }
                                            }
                                        }
                                    });
                                });
                            });

                            loadChartData();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
