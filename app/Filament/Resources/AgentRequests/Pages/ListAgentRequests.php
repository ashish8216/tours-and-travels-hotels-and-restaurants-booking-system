<?php

namespace App\Filament\Resources\AgentRequests\Pages;

use App\Filament\Resources\AgentRequests\AgentRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAgentRequests extends ListRecords
{
    protected static string $resource = AgentRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // CreateAction::make(),
        ];
    }

}
