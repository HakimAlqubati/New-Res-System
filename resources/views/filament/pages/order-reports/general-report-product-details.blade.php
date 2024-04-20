<x-filament::page>

    {{-- @if (isset($branch_id)) --}}
    {{-- <button wire:click="goBack">back</button> --}}
    <x-tables::table class="w-full text-sm text-left pretty  ">
        <thead>
            <x-tables::row class="header_report">
                <th colspan="2" class="{{ app()->getLocale() == 'en' ? 'no_border_right' : 'no_border_left' }}">
                    <p>{{ __('lang.general_report_of_products') }}</p>
                    <p>({{ $branch }})
                    </p>
                </th>
                <th class="no_border_right_left">
                    <p>{{ __('lang.start_date') . ': ' . $start_date }}</p>
                    <br>
                    <p>{{ __('lang.end_date') . ': ' . $end_date }}</p>
                </th>
                <th class="no_border_right_left">
                    <p>{{ __('lang.category') . ': (' . $category . ')' }}</p>

                </th>
                <th style="text-align: center; vertical-align: middle;"
                    class="{{ app()->getLocale() == 'en' ? 'no_border_left' : 'no_border_right' }}">
                    <img class="circle-image"
                        src="https://w7.pngwing.com/pngs/882/726/png-transparent-chef-cartoon-chef-photography-cooking-fictional-character-thumbnail.png"
                        alt="">
                </th>
            </x-tables::row>
            <x-tables::row>
                <th>{{ __('lang.product') }}</th>
                <th>{{ __('lang.unit') }}</th>
                <th>{{ __('lang.quantity') }}</th>
                <th>{{ __('lang.price') }}</th>
                <th>{{ __('lang.total_price') }}</th>
            </x-tables::row>
        </thead>
        <tbody>
            @foreach ($report_data as $data)
                <x-tables::row>
                    <x-tables::cell> {{ $data?->product_name }} </x-tables::cell>
                    <x-tables::cell> {{ $data?->unit_name }} </x-tables::cell>
                    <x-tables::cell> {{ $data?->quantity }} </x-tables::cell>
                    <x-tables::cell> {{ $data?->price }} </x-tables::cell>
                    <x-tables::cell> {{ $data?->total_price }} </x-tables::cell>
                </x-tables::row>
            @endforeach
        </tbody>

    </x-tables::table>
</x-filament::page>
