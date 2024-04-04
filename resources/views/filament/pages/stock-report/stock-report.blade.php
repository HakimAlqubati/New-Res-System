<x-filament::page>
    {{ $this->getTableFiltersForm() }}
    {{-- @if (isset($branch_id)) --}}
    <x-tables::table class="w-full text-sm text-left pretty displayschedule">
        <thead>
            <x-tables::row>
                <th>{{ __('lang.product_id') }} </th>
                <th>{{ __('lang.product') }}</th>
                <th>{{ __('lang.unit') }}</th>
                <th>{{ __('lang.quantity') }}</th>
                <th>{{ __('lang.unit_price') }}</th>
                <th>{{ __('lang.total_amount') }}</th>
            </x-tables::row>
        </thead>
        <tbody>
            @foreach ($stock_data as $key => $data)
                <x-tables::row>
                    <x-tables::cell> </x-tables::cell>
                    <x-tables::cell> </x-tables::cell>
                    <x-tables::cell> </x-tables::cell>
                    <x-tables::cell> </x-tables::cell>
                    <x-tables::cell> </x-tables::cell>
                    <x-tables::cell> </x-tables::cell>
                </x-tables::row>
            @endforeach

            <x-tables::row>
                <x-tables::cell></x-tables::cell>
                <x-tables::cell> </x-tables::cell>
                <x-tables::cell> </x-tables::cell>
                <x-tables::cell> </x-tables::cell>
                <x-tables::cell> </x-tables::cell>
                <x-tables::cell> </x-tables::cell>
            </x-tables::row>
        </tbody>

    </x-tables::table>
</x-filament::page>
