<x-filament::page>
    {{ $this->getTableFiltersForm() }}
    {{-- @if (isset($branch_id)) --}}
    <x-tables::table class="w-full text-sm text-left pretty  ">
        <thead>

            <x-tables::row>
                <th colspan="3">
                    Stock: ({{ $purchase_invoice_data['store_name'] }})
                </th>
                <th colspan="3">
                    Supplier: ({{ $purchase_invoice_data['supplier_name'] }})
                </th>
            </x-tables::row>
            <x-tables::row>
                <th>Product id </th>
                <th>Product name</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Unit price</th>
                <th>Total</th>
            </x-tables::row>
        </thead>
        <tbody>
            @php
                $total_sub_total = 0;
                $sum_unit_price = 0;
            @endphp
            @foreach ($purchase_invoice_data['results'] as $key => $invoice_item)
                @php
                    $unit_price = $invoice_item?->unit_price;
                    $sub_total = $invoice_item?->unit_price * $invoice_item?->quantity;

                    // Add the sub_total to the totalSubTotal variable
                    $total_sub_total += $sub_total;

                    // Add the unit_price to the sumUnitPrice variable
                    $sum_unit_price += $unit_price;
                @endphp
                <x-tables::row>
                    <x-tables::cell> {{ $invoice_item?->product_id }} </x-tables::cell>
                    <x-tables::cell> {{ $invoice_item?->product_name }} </x-tables::cell>
                    <x-tables::cell> {{ $invoice_item?->unit_name }} </x-tables::cell>
                    <x-tables::cell> {{ $invoice_item?->quantity }} </x-tables::cell>
                    <x-tables::cell> {{ $unit_price }} </x-tables::cell>
                    <x-tables::cell> {{ $sub_total }} </x-tables::cell>
                </x-tables::row>
            @endforeach

            <x-tables::row>
                <x-tables::cell colspan="4"> Final Total </x-tables::cell>
                <x-tables::cell> {{ $sum_unit_price }} </x-tables::cell>
                <x-tables::cell> {{ $total_sub_total }} </x-tables::cell>
            </x-tables::row>
        </tbody>

    </x-tables::table>
</x-filament::page>
