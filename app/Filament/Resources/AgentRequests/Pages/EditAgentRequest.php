<?php

namespace App\Filament\Resources\AgentRequests\Pages;

use App\Filament\Resources\AgentRequests\AgentRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAgentRequest extends EditRecord
{
    protected static string $resource = AgentRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
