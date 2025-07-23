<?php

namespace App\Filament\Resources;

use App\Exports\OrdersReadyExport;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\OrderTransfer;
use App\Tables\Columns\CountItemsTransfer;
use App\Tables\Columns\TotalTransfer;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class TransferOrderResource extends Resource
{
    protected static ?string $model = OrderTransfer::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Orders';
    protected static ?string $recordTitleAttribute = 'orders.id';

    protected static ?string $label = 'Transfers';
    protected static ?string $navigationLabel = 'Transfers list';
    public static ?string $slug = 'transfers-list';
    protected static function getNavigationLabel(): string
    {
        return __('lang.transfers_list');
    }
    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make('id')->label('Order id'),
                TextInput::make('customer.name')->label('customer'),
                TextInput::make('status')->label('Status'),
                TextInput::make('total')->label('total'),
                TextInput::make('branch.name')->label('branch'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->headerActions([
                   Tables\Actions\Action::make('export_selected_orders')
            ->label('Export Orders (Ready/Delivered)')
            ->icon('heroicon-o-download')
            ->form([
                Forms\Components\MultiSelect::make('order_ids')
                    ->label('Select Orders')
                    ->options(
                        \App\Models\Order::whereIn('status', [
                            \App\Models\Order::READY_FOR_DELEVIRY,
                            \App\Models\Order::DELEVIRED,
                        ])
                        ->orderBy('id', 'desc')
                        ->get()
                        ->pluck('id', 'id') // يمكن تخصيص طريقة العرض لو أردت
                    )
                    ->searchable()
                    ->required()
                    ->placeholder('Choose order numbers ...'),
            ])
            ->action(function (array $data) {
                $orders = \App\Models\Order::with(['orderDetails.product', 'branch'])
                    ->whereIn('id', $data['order_ids'] ?? [])
                    ->get();
                $export = new \App\Exports\OrdersReadyExport($orders);
                return \Maatwebsite\Excel\Facades\Excel::download($export, 'selected_orders.xlsx');
            }),
            ])
            ->columns([
                TextColumn::make('id')->label(__('lang.order_id'))->toggleable(isToggledHiddenByDefault: false)
                    ->copyable()
                    ->copyMessage('Order id copied')
                    ->copyMessageDuration(1500)
                    ->sortable()
                    ->searchable()
                    ->searchable(
                        isIndividual: true,
                        isGlobal: false
                    ),
                TextColumn::make('branch.name')->label(__('lang.branch')),
                TextColumn::make('customer.name')->label(__('lang.branch_manager'))->toggleable()
                    ->searchable(isIndividual: true)
                    ->tooltip(fn (Model $record): string => "By {$record->customer->name}"),

                CountItemsTransfer::make('item_counts')->label(__('lang.item_counts')),
                TotalTransfer::make('total_amount')->label(__('lang.total_amount')),
                TextColumn::make('transfer_date')
                    ->label(__('lang.transfer_date'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('lang.created_at'))
                    ->sortable(),
                // TextColumn::make('recorded'),
                // TextColumn::make('orderDetails'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                // Filter::make('active')
                //     ->query(fn (Builder $query): Builder => $query->where('active', true)),

                SelectFilter::make('customer_id')
                    ->searchable()
                    ->multiple()
                    ->label(__('lang.branch_manager'))->relationship('customer', 'name'),
                SelectFilter::make('branch_id')
                    ->searchable()
                    ->multiple()
                    ->label(__('lang.branch'))->relationship('branch', 'name'),
                Filter::make('created_at')
                    ->label(__('lang.created_at'))
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label(__('lang.from')),
                        Forms\Components\DatePicker::make('created_until')->label(__('lang.to')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(), 
                   Tables\Actions\Action::make('export_this_order')
                    ->label('Export to Excel')
                    ->icon('heroicon-o-download')
                    // اجلب الطلب الحالي فقط مع تفاصيله
                    ->action(function (Model $record) {
                        $order  = $record->load(['orderDetails.product', 'branch']);
                        $export = new OrdersReadyExport(collect([$order]));
                        return Excel::download($export, 'order_' . $order->id . '.xlsx');
                    })
                    // يظهر فقط لو حالة الطلب ready_for_delivery أو delevired
                    ->visible(function (Model $record) {
                        return in_array($record->status, [
                            Order::READY_FOR_DELEVIRY,
                            Order::DELEVIRED,
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransferOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }


    protected function getTableReorderColumn(): ?string
    {
        return 'sort';
    }

    protected function getTableRecordActionUsing(): ?Closure
    {
        return null;
    }



    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', OrderTransfer::search($searchQuery)->keys());
        }

        return $query;
    }
    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(Model $model): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()->InTransfer();
    }

    protected static function getNavigationBadge(): ?string
    {
        return count(OrderTransfer::inTransfer()->select('orders.id')->get()->toArray());
    }
}