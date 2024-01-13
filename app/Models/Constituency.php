<?php

namespace App\Models;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Constituency extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function county(): BelongsTo {
        return $this->belongsTo(County::class);
    }

    public static function getForm(): array {
        return [
            TextInput::make('constituency')
                ->required()
                ->maxLength(255),
            Select::make('county_id')
                ->relationship('county', 'county')
                ->searchable()
                ->createOptionForm(County::getForm())
                ->editOptionForm(County::getForm())
                ->preload()
                ->required(),
        ];
    }
}
