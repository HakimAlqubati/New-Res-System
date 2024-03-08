<?php

namespace App\Core;

use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

class BaseAuthResource extends Resource
{

    public static ?string $permissionSlug = "";

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit(Model $record): bool
    {
        return true;
    }

    public static function canDelete(Model $record): bool
    {
        return true;
    }

    public static function canDeleteAny(): bool
    {
        return true;
    }

    public static function canView(Model $record): bool
    {
        return true;
    }
}
