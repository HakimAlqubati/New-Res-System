<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport2 implements FromArray, WithHeadings
{
    protected $orders;

    public function __construct(Collection $orders)
    {
        $this->orders = $orders;
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->orders->load(['customer', 'branch', 'orderDetails.product', 'orderDetails.unit']) as $order) {
            // ✅ 1. Header Row (Order info)
            $data[] = [
                'Row Type' => 'Header',
                'Order ID' => $order->id,
                'Branch ID' => $order->branch_id,
                'Branch Name' => $order->branch->name ?? '',
                'Customer ID' => $order->customer_id,
                'Customer Name' => $order->customer->name ?? '',
                'Status' => $order->status,
                'Notes' => $order->notes,
                'Created At' => $order->created_at,
                'Product ID' => '',
                'Product Name' => '',
                'Unit ID' => '',
                'Unit Name' => '',
                'Quantity' => '',
                'Price' => '',
                'Available Quantity' => '',
            ];

            // ✅ 2. Detail Rows (Products in order)
            foreach ($order->orderDetails as $detail) {
                $data[] = [
                    'Row Type' => 'Detail',
                    'Order ID' => $order->id,
                    'Branch ID' => '',
                    'Branch Name' => '',
                    'Customer ID' => '',
                    'Customer Name' => '',
                    'Status' => '',
                    'Notes' => '',
                    'Created At' => '',
                    'Product ID' => $detail->product_id,
                    'Product Name' => $detail->product->name ?? '',
                    'Unit ID' => $detail->unit_id,
                    'Unit Name' => $detail->unit->name ?? '',
                    'Quantity' => $detail->quantity,
                    'Price' => $detail->price,
                    'Available Quantity' => $detail->available_quantity,
                ];
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Row Type',
            'Order ID',
            'Branch ID',
            'Branch Name',
            'Customer ID',
            'Customer Name',
            'Status',
            'Notes',
            'Created At',
            'Product ID',
            'Product Name',
            'Unit ID',
            'Unit Name',
            'Quantity',
            'Price',
            'Available Quantity',
        ];
    }
}
