<?php

namespace App\Models;

use App\Observers\SchoolObserver;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class School extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function county(): BelongsTo {
        return $this->belongsTo(County::class);
    }

    public function constituency(): BelongsTo {
        return $this->belongsTo(Constituency::class);
    }

    public function ward(): BelongsTo {
        return $this->belongsTo(Ward::class);
    }

    public static function getForm(): array {
        return [
            TextInput::make('name')
                ->columnSpanFull(),
            Grid::make(2)
                ->schema([
                    TextInput::make('password')
                        ->password()
                        ->required()
                        ->maxLength(255)
                        ->confirmed(),
                    TextInput::make('password_confirmation')
                        ->password()
                        ->maxLength(255)
                        ->required(),
                ]),
            Grid::make(3)
                ->schema([
                    Select::make('county_id')
                        ->relationship('county', 'county')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->required(),
                    Select::make('constituency_id')
                        ->relationship('constituency', 'constituency', modifyQueryUsing: function(Builder $query, Get $get) {
                            return $query->where('county_id', $get('county_id'));
                        })
                        ->searchable()
                        ->live()
                        ->preload()
                        ->required(),
                    Select::make('ward_id')
                        ->relationship('ward', 'ward', modifyQueryUsing: function(Builder $query, Get $get) {
                            return $query->where('constituency_id', $get('constituency_id'));
                        })
                        ->searchable()
                        ->preload()
                        ->required(),
                ])
            ];
    }

    public static function booted()
    {
        parent::booted();

        self::observe(SchoolObserver::class);
    }
}
