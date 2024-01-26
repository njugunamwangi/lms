<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ward extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function county(): BelongsTo {
        return $this->belongsTo(County::class);
    }

    public function constituency(): BelongsTo {
        return $this->belongsTo(Constituency::class);
    }

    public static function getForm(): array {
        return [
            TextInput::make('ward')
                ->required()
                ->columnSpanFull()
                ->maxLength(255),
            Grid::make(2)
                ->schema([
                    Select::make('county_id')
                        ->relationship('county', 'county')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->createOptionForm(County::getForm())
                        ->editOptionForm(County::getForm())
                        ->required(),
                    Select::make('constituency_id')
                        ->relationship('constituency', 'constituency', modifyQueryUsing: function(Builder $query, Get $get) {
                            return $query->where('county_id', $get('county_id'));
                        })
                        ->searchable()
                        ->preload()
                        ->createOptionForm(Constituency::getForm())
                        ->editOptionForm(Constituency::getForm())
                        ->required(),
                ])
            ];
    }
}
