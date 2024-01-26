
    public static function booted()
    {
        parent::booted();

        self::observe(SchoolObserver::class);
    }
