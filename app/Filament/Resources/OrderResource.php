<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use App\Tables\Columns\count_items_order;
use App\Tables\Columns\TotalOrder;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
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
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource implements HasShieldPermissions
{

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'publish'
        ];
    }

    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Orders';
    protected static ?string $recordTitleAttribute = 'id';
    protected static function getNavigationLabel(): string
    {
        return __('lang.orders');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id')->label(__('lang.order_id'))->disabledOn('edit'),
                Select::make('customer_id')
                    ->disabledOn('edit')
                    ->label(__('lang.branch_manager'))
                    ->options(User::select('id', 'name')->get()->pluck('name', 'id')),
                Select::make('branch_id')
                    ->label(__('lang.branch'))
                    ->disabledOn('edit')
                    ->options(Branch::select('id', 'name')->get()->pluck('name', 'id')),
                Select::make('status')
                    ->label(__('lang.order_status'))
                    ->options([
                        Order::ORDERED => 'Ordered',
                        Order::READY_FOR_DELEVIRY => 'Ready for delivery',
                        Order::PROCESSING => 'processing',
                        Order::DELEVIRED => 'delevired',
                    ]),
                // TextInput::make('store_emp_responsiple.name')->label(__('lang.store_responsiple'))->columnSpanFull(),
                Textarea::make('notes')->label(__('lang.notes_from_store'))->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label(__('lang.order_id'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->copyable()
                    ->copyMessage(__('lang.order_id_copied'))
                    ->copyMessageDuration(1500)
                    ->sortable()->searchable()
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('customer.name')->label(__('lang.branch_manager'))->toggleable()
                    ->searchable(isIndividual: true)
                    ->tooltip(fn (Model $record): string => "By {$record->customer->name}"),
                TextColumn::make('branch.name')->label(__('lang.branch')),
                BadgeColumn::make('status')
                    ->label(__('lang.order_status'))
                    ->colors([
                        'primary',
                        'secondary' => static fn ($state): bool => $state === Order::PENDING_APPROVAL,
                        'warning' => static fn ($state): bool => $state === Order::READY_FOR_DELEVIRY,
                        'success' => static fn ($state): bool => $state === Order::DELEVIRED,
                        'danger' => static fn ($state): bool => $state === Order::PROCESSING,
                    ])
                    ->iconPosition('after'),
                count_items_order::make('item_counts')->label(__('lang.item_counts')),
                TotalOrder::make('total_amount')->label(__('lang.total_amount')),
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
                SelectFilter::make('status')
                    ->label(__('lang.order_status'))
                    ->multiple()
                    ->searchable()
                    ->options([
                        'ordered' => 'Ordered',
                        'processing' => 'Processing',
                        'ready_for_delivery' => 'Ready for deleviry',
                        'delevired' => 'Delevired',
                        'pending_approval' => 'Pending approval',
                    ]),
                SelectFilter::make('customer_id')
                    ->searchable()
                    ->multiple()
                    ->label(__('lang.branch_manager'))->relationship('customer', 'name'),
                SelectFilter::make('branch_id')
                    ->searchable()
                    ->multiple()
                    ->label(__('lang.branch'))->relationship('branch', 'name'),
                // Filter::make('active')->label(__('lang.active')),
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
                    }),
                Tables\Filters\TrashedFilter::make(),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                // Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'order-report-custom' => Pages\OrderReportCustom::route('/order-report-custom'),

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


    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Order::search($searchQuery)->keys());
        }

        return $query;
    }
    public static function canCreate(): bool
    {
        return false;
    }


    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->id;
        dd();
        return $record->this->id;
    }
}
