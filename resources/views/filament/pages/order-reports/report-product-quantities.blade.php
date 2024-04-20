<x-filament::page>
    {{ $this->getTableFiltersForm() }}
    {{-- @if (isset($branch_id)) --}}
    <x-tables::table class="w-full text-sm text-left pretty  ">
        <thead>




            <x-tables::row class="header_report">
                <th class="{{ app()->getLocale() == 'en' ? 'no_border_right' : 'no_border_left' }}">
                    <p>{{ __('lang.report_product_quantities') }}</p>
                    <p>({{ isset($product_id) && is_numeric($product_id) ? \App\Models\Product::find($product_id)->name : __('lang.choose_product') }})
                    </p>
                </th>
                <th colspan="2" class="no_border_right_left">
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
                <th>{{ __('lang.branch') }}</th>
                <th>{{ __('lang.unit') }}</th>
                <th>{{ __('lang.quantity') }}</th>
                <th>{{ __('lang.price') }}</th>
            </x-tables::row>
        </thead>
        <tbody>
            @foreach ($report_data as $data)
                <x-tables::row>

                    <x-tables::cell> {{ $data?->branch }} </x-tables::cell>
                    <x-tables::cell> {{ $data?->unit }} </x-tables::cell>
                    <x-tables::cell> {{ $data?->quantity }} </x-tables::cell>
                    <x-tables::cell> {{ $data?->price }} </x-tables::cell>


                </x-tables::row>
            @endforeach

            <x-tables::row>
                <x-tables::cell colspan="2"> {{ __('lang.total_quantity') }} </x-tables::cell>

                <x-tables::cell> {{ $total_quantity }} </x-tables::cell>
                <x-tables::cell> {{ $total_price }} </x-tables::cell>
            </x-tables::row>
        </tbody>

    </x-tables::table>
</x-filament::page>
