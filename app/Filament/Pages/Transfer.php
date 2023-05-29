<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Transfer extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.transfer';

    protected static ?string $title = 'Transfers';

    protected static ?string $navigationLabel = 'Transfers';

    protected static ?string $slug = 'custom-url-slug';

    protected static ?string $navigationGroup = 'Orders';

    // protected static function shouldRegisterNavigation(): bool
    // {
    //     return auth()->user()->canManageSettings();
    // }
}
