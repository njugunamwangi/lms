
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
