<?php

namespace App\Filament\Resources\OrderPurchaseResource\Pages;

use App\Filament\Resources\OrderPurchaseResource;
use App\Models\Order;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderPurchase extends CreateRecord
{
    protected static string $resource = OrderPurchaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        
        $data['is_purchased'] = 1;
        $data['status'] = Order::DELEVIRED;
        $data['branch_id'] = auth()->user()->branch->id;
        $data['customer_id'] = auth()->user()->id;

        // dd($data['purchaseInvoiceDetails']);
        return $data;
    }

}
