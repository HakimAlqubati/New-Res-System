<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Forms\Components\Select;
use App\Models\User;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class Transfer extends Page
{
    public $name = 'hi';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.transfer';

    protected static ?string $title = 'Transfers';

    protected static ?string $navigationLabel = 'Transfers';

    protected static ?string $slug = 'transfers';

    protected static ?string $navigationGroup = 'Orders';

    public function mount()
    {
        $this->name = '';
    }

    public function render(): View
    {
        return view('filament.pages.transfer', [
            'name' => $this->name,
        ]);
    }
}
