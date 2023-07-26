<?php

namespace App\Filament\Resources\OrderResource\Pages;
 
use App\Filament\Resources\TransferOrderResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Pages\Actions\Action;
 
class ViewOrderTransfer extends ViewRecord
{
    protected static string $resource = TransferOrderResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('Export to Excel')
                ->action('exportToExcel'),
        ];
    }

    public function exportToExcel()
    {
        return redirect('orders/export-transfer/' . $this->record->id);
    }
}
