<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $user_id = $this->record->id;
        $role_id = $this->data['role_id'];
        DB::table('model_has_roles')->insert([
            'role_id' => $role_id,
            'model_id' => $user_id,
            'model_type' => 'App\Models\User'
        ]);
    }

    // protected function mutateFormDataBeforeSave(array $data): array
    // {
    //     dd($data);
    //     return $data;
    // }

}
