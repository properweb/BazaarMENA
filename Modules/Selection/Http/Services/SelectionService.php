<?php

namespace Modules\Selection\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Modules\Selection\Entities\Category;
use Modules\Selection\Entities\Country;
use Modules\Selection\Entities\State;
use Modules\Selection\Entities\City;
use Carbon\Carbon;


class SelectionService
{

    /**
     * Get Categories
     *
     * @return array
     */
    public function getCategory(): array
    {
        return Category::where('parent_id', '0')->select('id', 'title')->get()->toArray();
    }

    /**
     * Get Countries
     *
     * @return array
     */
    public function getCountries(): array
    {
        return Country::select('id', 'name', 'phonecode')->get()->toArray();
    }

    /**
     * Get Country By id
     * @param $id
     * @return array
     */
    public function getCountryById($id): array
    {
        return Country::where('id',$id)->select('id', 'name', 'phonecode')->get()->toArray();
    }

    /**
     * Get State
     * @param $country_id
     * @return array
     */
    public function getState($country_id): array
    {
        return State::where('country_id', $country_id)->select('id', 'name')->get()->toArray();
    }

    /**
     * Get State By id
     * @param $id
     * @return array
     */
    public function getStateById($id): array
    {
        return State::where('id',$id)->select('id', 'name')->get()->toArray();
    }

    /**
     * Get City
     * @param $state_id
     * @return array
     */
    public function getCity($state_id): array
    {
        $countries = City::where('state_id', $state_id)->select('id', 'name')->get()->toArray();
        return $countries;
    }

    /**
     * Get City By id
     * @param $id
     * @return array
     */
    public function getCityById($id): array
    {
        return City::where('id',$id)->select('id', 'name')->get()->toArray();
    }
}
