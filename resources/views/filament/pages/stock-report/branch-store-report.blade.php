<x-filament::page>
    {{ $this->getTableFiltersForm() }}
    {{-- @if (isset($branch_id)) --}}
    <x-tables::table class="w-full text-sm text-left pretty  ">
        <thead>

            <x-tables::row>
                <th colspan="2">
                    {{ __('lang.branch_store_report') }}
                </th>
                <th colspan="2">

                    {{ __('lang.branch') }}:
                    ({{ isset($branch_id) && is_numeric($branch_id) ? \App\Models\Branch::find($branch_id)->name : __('lang.choose_branch') }})
                </th>
            </x-tables::row>
            <x-tables::row>
                <th>Product id </th>
                <th>Product name</th>
                <th>Unit</th>
                <th>Quantity</th>
            </x-tables::row>
        </thead>
        <tbody>

            @foreach ($branch_store_report_data as $key => $report_item)
                <x-tables::row>
                    <x-tables::cell> {{ $report_item?->product_id }} </x-tables::cell>
                    <x-tables::cell> {{ $report_item?->product_name }} </x-tables::cell>
                    <x-tables::cell> {{ $report_item?->unit_name }} </x-tables::cell>
                    <x-tables::cell> {{ $report_item?->total_quantity }} </x-tables::cell>
                </x-tables::row>
            @endforeach
            <x-tables::row>
                <x-tables::cell colspan="3">Total</x-tables::cell>
                <x-tables::cell>{{ $total_quantity }}</x-tables::cell>
            </x-tables::row>
        </tbody>

    </x-tables::table>
</x-filament::page>
