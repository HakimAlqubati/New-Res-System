<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Select;
use App\Models\User;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget;

class Transfer extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.transfer';

    protected static ?string $title = 'Transfers';

    protected static ?string $navigationLabel = 'Transfers';

    protected static ?string $slug = 'transfers';

    protected static ?string $navigationGroup = 'Orders';

    // protected static function shouldRegisterNavigation(): bool
    // {
    //     return auth()->user()->canManageSettings();
    // }

    protected function getActions(): array
    {
        return [
            Action::make('settings')
                ->label('settings')
                ->action('openSettingsModal')
                ->color('secondary')
                ->icon('heroicon-s-cog'),
            Action::make('updateAuthor')
                ->action(function (array $data): void {
                    $this->record->author()->associate($data['authorId']);
                    $this->record->save();
                })
                ->form([
                    Select::make('authorId')
                        ->label('Author')
                        ->options(User::query()->pluck('name', 'id'))
                        ->required(),
                ])
        ];
    }


    public function openSettingsModal(): void
    {
        $this->dispatchBrowserEvent('open-settings-modal');
    }
 

    protected function getHeaderWidgetsColumns(): int | array
    {
        return [
            'md' => 4,
            'xl' => 5,
        ];
    }
}
