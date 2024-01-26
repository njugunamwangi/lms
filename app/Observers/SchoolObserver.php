<?php

namespace App\Observers;

use App\Models\School;

class SchoolObserver
{
    public function creating(School $school) {
        $fullAddress = 'COU' . $school->county->id . 'CON' . $school->county->id . 'WAR' . $school->county->id;

        $result = app('geocoder')->geocode($fullAddress)->get();

        $coordinates = $result[0]->getCoordinates();

        $school->lat = $coordinates->getLatitude();
        $school->long = $coordinates->getLongitude();
    }
}
