<?php

namespace App\Filament\Resources\Agents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AgentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),

                TextColumn::make('business_name')
                    ->searchable(),

                TextColumn::make('business_type')
                    ->label('Business Types')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) return 'N/A';

                        $types = explode(',', $state);
                        $badges = array_map(function($type) {
                            // Define colors for each type
                            $color = match(trim($type)) {
                                'hotel' => 'info',
                                'restaurant' => 'warning',
                                'both' => 'success',
                                'tour_guide' => 'purple',
                                default => 'gray'
                            };

                            return '<span class="px-2 py-1 text-xs rounded-full bg-' . $color . '-100 text-' . $color . '-800">' . ucfirst(trim($type)) . '</span>';
                        }, $types);

                        return implode(' ', $badges);
                    })
                    ->html()
                    ->searchable(),

                TextColumn::make('phone')
                    ->searchable(),

                TextColumn::make('address')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Add filter for business types
                SelectFilter::make('business_type')
                    ->label('Business Type')
                    ->options([
                        'hotel' => 'Hotel',
                        'restaurant' => 'Restaurant',
                        'both' => 'Both (Hotel & Restaurant)',
                        'tour_guide' => 'Tour/Guide',
                    ])
                    ->query(function ($query, $data) {
                        if (!empty($data['value'])) {
                            $query->where('business_type', 'LIKE', '%' . $data['value'] . '%');
                        }
                        return $query;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                // Single delete with role update
                DeleteAction::make()
                    ->before(function ($record) {
                        // Change role of linked user
                        if ($record->user) {
                            $record->user->update([
                                'role' => 'user',
                            ]);
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // Bulk delete with role update
                    DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if ($record->user) {
                                    $record->user->update([
                                        'role' => 'user',
                                    ]);
                                }
                            }
                        }),
                ]),
            ]);
    }
}
