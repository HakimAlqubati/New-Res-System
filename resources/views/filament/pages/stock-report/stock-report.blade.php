<x-filament::page>
    {{ $this->getTableFiltersForm() }}
    {{-- @if (isset($branch_id)) --}}
    <x-tables::table class="w-full text-sm text-left pretty displayschedule">
        <thead>
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
