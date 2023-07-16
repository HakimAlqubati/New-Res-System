<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Contracts\HasTable;

class MyCustomPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.my-custom-page';

    public function getTitle(): string
    {
        return 'My Custom Page WoW';
    }
    public function table(): Table
    {
        return Table::make('my-table');
    }
    public function getView(): string
    {
        return 'my-custom-page';
    }

    public function render(): View2
    {

        $table = $this->table();

        $table->addColumn(TextColumn::make('name'));
        $table->addColumn(TextColumn::make('email'));

        $table->addFilter(SelectFilter::make('status', [
            'Active' => 'Active',
            'Inactive' => 'Inactive',
        ]));

        return view('my-custom-page', [
            'table' => $table,
        ]);
    }
}
