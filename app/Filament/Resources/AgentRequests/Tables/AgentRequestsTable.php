<?php

namespace App\Filament\Resources\AgentRequests\Tables;

use App\Models\AgentRequest;
use App\Models\User;
use App\Models\Agent;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AgentApprovedMail;

class AgentRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('business_name')
                    ->label('Business')
                    ->searchable(),

                TextColumn::make('owner_name')
                    ->label('Owner'),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('business_type')
                    ->label('Business Types')
                    ->formatStateUsing(function ($state) {
                       if (empty($state) || trim($state) === '')
                        {
                        return 'N/A';
                        }
                         $types = explode(',', $state);
                         $formattedTypes = array_map(function($type) {
                          return trim($type); // Just trim, no HTML yet
                        }, $types);
                         return implode(', ', $formattedTypes);

                    })
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Requested At'),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (AgentRequest $record) => $record->status === 'pending')
                        ->action(function (AgentRequest $record) {
                            if (User::where('email', $record->email)->exists()) {
                                throw new \Exception('User with this email already exists.');
                            }

                            $plainPassword = Str::random(10);

                            $user = User::create([
                                'name' => $record->owner_name,
                                'email' => $record->email,
                                'password' => Hash::make($plainPassword),
                                'role' => 'agent',
                            ]);

                            Agent::create([
                                'user_id' => $user->id,
                                'business_name' => $record->business_name,
                                'business_type' => $record->business_type,
                                'phone' => $record->phone,
                                'address' => $record->address,
                            ]);

                            $record->update(['status' => 'approved']);

                            Mail::to($record->email)->send(
                                new AgentApprovedMail($user, $plainPassword)
                            );
                        }),

                    Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (AgentRequest $record) => $record->status === 'pending')
                        ->action(fn (AgentRequest $record) => $record->update(['status' => 'rejected'])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
