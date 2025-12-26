<?php

namespace App\Filament\Resources\AgentRequests;

use App\Filament\Resources\AgentRequests\Pages\CreateAgentRequest;
use App\Filament\Resources\AgentRequests\Pages\EditAgentRequest;
use App\Filament\Resources\AgentRequests\Pages\ListAgentRequests;
use App\Filament\Resources\AgentRequests\Schemas\AgentRequestForm;
use App\Filament\Resources\AgentRequests\Tables\AgentRequestsTable;
use App\Models\AgentRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AgentRequestResource extends Resource
{
    protected static ?string $model = AgentRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'business_name';

    public static function form(Schema $schema): Schema
    {
        return AgentRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AgentRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAgentRequests::route('/'),
            'create' => CreateAgentRequest::route('/create'),
            'edit' => EditAgentRequest::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
{
    return false;
}
}
