<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersReadyExport implements FromCollection, WithHeadings
{
    protected $orders;
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        $rows = [];

        foreach ($this->orders as $order) {
            foreach ($order->orderDetails as $item) {
                $rows[] = [
                    'Product'       => $item->product->name ?? '—',
                    'Description'   => $this->generateDescription($order, $item),
                    'From Location' => optional($this->getDefaultStore())->name ?? 'Not set',
                    'To Location'   => $order->branch?->name ?? 'Not set',
                    'Quantity'      => $item->quantity,
                ];
            }
        }
        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'Product',
            'Description',
            'From Location',
            'To Location',
            'Quantity',
        ];
    }

    // توليد وصف افتراضي بناء على الطلب والبند (يمكنك تعديله كما تحب)
    protected function generateDescription($order, $item)
    {
        $productName = $item->product?->name ?? 'منتج غير محدد';
        $fromStore   = optional($this->getDefaultStore())->name ?? 'Not set';
        $toBranch    = $order->branch?->name ?? 'Not set';

        return "{$productName} Transfered from {$fromStore} to {$toBranch}";

    }

    protected function getDefaultStore()
    {
        return \App\Models\Store::where('default_store', 1)->first();
    }
}