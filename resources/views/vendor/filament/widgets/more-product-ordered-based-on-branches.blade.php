@php
    $heading = $this->getHeading();
    $filters = $this->getFilters();
@endphp

<x-filament::widget class="filament-widgets-chart-widget" style="direction: rtl">
    <x-filament::card>
        @if ($heading || $filters)
            <div class="flex items-center justify-between gap-8">
                @if ($heading)
                    <x-filament::card.heading>
                        {{ $heading }}
                    </x-filament::card.heading>
                @endif

                <div>

                    <div>
                        <p>إختر الفرع</p>
                        <select wire:model="branchid" @class([
                            'block h-10 rounded-lg border-gray-300 text-gray-900 shadow-sm outline-none transition duration-75 focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500',
                            'dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:border-primary-500' => config(
                                'filament.dark_mode'),
                        ]) wire:loading.class="animate-pulse">
                            @foreach ($branches as $value => $label)
                                <option value="{{ $value }}">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <p>إختر الشهر</p>
                        <select wire:model="month" @class([
                            'text-gray-900 border-gray-300 block h-10 transition duration-75 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500',
                            'dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:focus:border-primary-500' => config(
                                'filament.dark_mode'),
                        ])>
                            <option value="0">-إختر الشهر-</option>
                            <option value="1">يناير</option>
                            <option value="2">فبراير</option>
                            <option value="3">مارس</option>
                            <option value="4">أبريل</option>
                            <option value="5">مايو</option>
                            <option value="6">يونيو</option>
                            <option value="7">يوليو</option>
                            <option value="8">أغسطس</option>
                            <option value="9">سبتمبر</option>
                            <option value="10">أكتوبر</option>
                            <option value="11">نوفمبر</option>
                            <option value="12">ديسمبر</option>
                        </select>
                    </div>

                </div>
                <div>
                    <div>
                        <p>إختر السنة</p>
                        <select wire:model="yearid" @class([
                            'text-gray-900 border-gray-300 block h-10 transition duration-75 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500',
                            'dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:focus:border-primary-500' => config(
                                'filament.dark_mode'),
                        ])>

                            <option value="0000">-الكل- </option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>


                    <div>
                        <p>عدد المنتجات الظاهرة في الرسم البياني</p>
                        <select wire:model="productscount" @class([
                            'text-gray-900 border-gray-300 block h-10 transition duration-75 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500',
                            'dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:focus:border-primary-500' => config(
                                'filament.dark_mode'),
                        ])>


                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                        </select>
                    </div>
                </div>
            </div>

            <x-filament::hr />
        @endif

        <div {!! ($pollingInterval = $this->getPollingInterval()) ? "wire:poll.{$pollingInterval}=\"updateChartData\"" : '' !!}>
            <canvas x-data="{
                chart: null,
            
                init: function() {
                    let chart = this.initChart()
            
                    $wire.on('updateChartData', async ({ data }) => {
                        chart.data = this.applyColorToData(data)
                        chart.update('resize')
                    })
            
                    $wire.on('filterChartData', async ({ data }) => {
                        chart.destroy()
                        chart = this.initChart(data)
                    })
                },
            
                initChart: function(data = null) {
                    data = data ?? {{ json_encode($this->getCachedData()) }}
            
                    return (this.chart = new Chart($el, {
                        type: '{{ $this->getType() }}',
                        data: this.applyColorToData(data),
                        options: {{ json_encode($this->getOptions()) }} ?? {},
                    }))
                },
            
                applyColorToData: function(data) {
                    data.datasets.forEach((dataset, datasetIndex) => {
                        if (!dataset.backgroundColor) {
                            data.datasets[datasetIndex].backgroundColor = getComputedStyle(
                                $refs.backgroundColorElement,
                            ).color
                        }
            
                        if (!dataset.borderColor) {
                            data.datasets[datasetIndex].borderColor = getComputedStyle(
                                $refs.borderColorElement,
                            ).color
                        }
                    })
            
                    return data
                },
            }" wire:ignore @if ($maxHeight = $this->getMaxHeight())
                style="max-height: {{ $maxHeight }}"
                @endif
                >
                <span x-ref="backgroundColorElement" @class([
                    'text-gray-50',
                    'dark:text-gray-300' => config('filament.dark_mode'),
                ])></span>

                <span x-ref="borderColorElement" @class([
                    'text-gray-500',
                    'dark:text-gray-200' => config('filament.dark_mode'),
                ])></span>
            </canvas>
        </div>
    </x-filament::card>
</x-filament::widget>
<style>
    select{
        min-width: 180px !important;
    }
</style>