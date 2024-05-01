<?php

namespace App\Filament\Resources\SystemSettingResource\Pages;

use App\Filament\Resources\SystemSettingResource;
use App\Models\SystemSetting;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSystemSetting extends EditRecord
{
    protected static string $resource = SystemSettingResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    // protected function mutateFormDataBeforeSave(array $data): array
    // {
    //     $resource = static::getResource();
    //     $currentCaluclatingMethod = SystemSetting::select('calculating_orders_price_method')->first()?->calculating_orders_price_method;
    //     if ($currentCaluclatingMethod != $data['calculating_orders_price_method']) {
    //     }
    //     $this->redirect($resource::getUrl('index'));
    //     // dd($data['calculating_orders_price_method'], $currentCaluclatingMethod, $resource, $resource::getUrl('index'));
    //     return $data;
    // }
}
