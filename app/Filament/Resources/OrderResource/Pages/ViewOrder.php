<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Pages\Actions\Action;


class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('Export to Excel')
                ->action('exportToExcel'),
        ];
    }

    public function exportToExcel()
    {
        return redirect('orders/export/' . $this->record->id);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    { 
        $data['customer_id'] = $this?->record?->customer?->name;
        $data['branch_id'] = $this?->record?->branch?->name;
        return $data;
    }
}
