<x-filament::page>
    {{ $this->getTableFiltersForm() }}
    {{-- @if (isset($branch_id)) --}}
    <x-tables::table class="w-full text-sm text-left pretty  ">
        <thead>

            {{-- <x-tables::row>
                <th colspan="3">
                    Stock: ({{ $purchase_invoice_data['store_name'] }})
                </th>
                <th colspan="2">
                    Supplier: ({{ $purchase_invoice_data['supplier_name'] }})
                </th>
            </x-tables::row> --}}
            <x-tables::row>
                <th>{{ __('lang.product_id') }} </th>
                <th>{{ __('lang.product') }}</th>
                <th>{{ __('lang.unit') }}</th>
                <th>{{ __('lang.purchased_qty') }}</th>
                <th>{{ __('lang.qty_sent_to_branches') }}</th>
                <th>{{ __('lang.qty_in_stock') }}</th>
            </x-tables::row>
        </thead>
        <tbody>

            @foreach ($stores_report_data as $key => $report_item)
                <x-tables::row>
                    <x-tables::cell> {{ $report_item?->product_id }} </x-tables::cell>
                    <x-tables::cell> {{ $report_item?->product_name }} </x-tables::cell>
                    <x-tables::cell> {{ $report_item?->unit_name }} </x-tables::cell>
                    <x-tables::cell> {{ $report_item?->income }} </x-tables::cell>
                    <x-tables::cell> {{ $report_item?->ordered }} </x-tables::cell>
                    <x-tables::cell> {{ $report_item?->remaining }} </x-tables::cell>
                </x-tables::row>
            @endforeach
        </tbody>

    </x-tables::table>
</x-filament::page>
