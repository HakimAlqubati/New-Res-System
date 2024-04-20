<x-filament::page>
    {{ $this->getTableFiltersForm() }}
    {{-- @if (isset($branch_id)) --}}
    <x-tables::table class="w-full text-sm text-left pretty  ">
        <thead>




            <x-tables::row class="header_report">
                <th class="{{ app()->getLocale() == 'en' ? 'no_border_right' : 'no_border_left' }}">
                    <p>{{ __('lang.general_report_of_products') }}</p>
                    <p>({{ isset($branch_id) && is_numeric($branch_id) ? \App\Models\Branch::find($branch_id)->name : __('lang.choose_branch') }})
                    </p>
                </th>
                <th class="no_border_right_left">
                    <p>{{ __('lang.start_date') . ': ' . $start_date }}</p>
                    <br>
                    <p>{{ __('lang.end_date') . ': ' . $end_date }}</p>
                </th>
                <th style="text-align: center; vertical-align: middle;"
                    class="{{ app()->getLocale() == 'en' ? 'no_border_left' : 'no_border_right' }}">
                    <img class="circle-image"
                        src="https://w7.pngwing.com/pngs/882/726/png-transparent-chef-cartoon-chef-photography-cooking-fictional-character-thumbnail.png"
                        alt="">
                </th>
            </x-tables::row>
            <x-tables::row>
                <th>{{ __('lang.category') }}</th>

                <th>{{ __('lang.quantity') }}</th>
                <th>{{ __('lang.total') }}</th>
            </x-tables::row>
        </thead>
        <tbody>
            @foreach ($report_data as $data)
                <x-tables::row>

                    <x-tables::cell>
                        <a href="{{ url($data?->url_report_details) }}"> {{ $data?->category }}</a>
                    </x-tables::cell>
                    <x-tables::cell> {{ $data?->quantity }} </x-tables::cell>
                    <x-tables::cell> {{ $data?->amount . ' ' . $data?->symbol }} </x-tables::cell>
                </x-tables::row>
            @endforeach
        </tbody>

    </x-tables::table>
</x-filament::page>
