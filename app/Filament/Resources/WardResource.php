<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WardResource\Pages;
use App\Filament\Resources\WardResource\RelationManagers;
use App\Models\Constituency;
use App\Models\County;
use App\Models\Ward;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WardResource extends Resource
{
    protected static ?string $model = Ward::class;

    protected static ?string $navigationGroup = 'Locations';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ward')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Grid::make(2)
                    ->schema([
                        Forms\Components\Select::make('county_id')
                            ->relationship('county', 'county')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->createOptionForm(County::getForm())
                            ->editOptionForm(County::getForm())
                            ->required(),
                        Forms\Components\Select::make('constituency_id')
                            ->relationship('constituency', 'constituency', modifyQueryUsing: function(Builder $query, Get $get) {
                                return $query->where('county_id', $get('county_id'));
                            })
                            ->searchable()
                            ->preload()
                            ->createOptionForm(Constituency::getForm())
                            ->editOptionForm(Constituency::getForm())
                            ->required(),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ward')
                    ->searchable(),
                Tables\Columns\TextColumn::make('county.county')
                    ->sortable(),
                Tables\Columns\TextColumn::make('constituency.constituency')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListWards::route('/'),
            // 'create' => Pages\CreateWard::route('/create'),
            // 'edit' => Pages\EditWard::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
