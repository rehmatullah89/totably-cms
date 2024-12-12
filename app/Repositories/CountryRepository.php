<?php


namespace App\Repositories;

use App\Models\Idea\Country;
use App\Idea\Types\ExceptionType;
use Illuminate\Http\Request;

/**
 * Description: The following repository is used to handle all function related to countrys
 * Class UserAccountRepository
 * @package App\Repositories\User
 */
class CountryRepository
{
    use ExceptionType;

    protected $country;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Country $country, Request $request)
    {
        $this->request = $request;
        $this->country = $country;
    }

    /**
     * Description: This function will return all countries
     * @author Hassan Mehmood - I2L
     * @return \App\Models\Idea\Country
     */
    public function findAll()
    {
        $countries = $this->country::with("translations")->get();
        if (!$countries) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        return $countries;
    }

    /**
     * Description: This function will return one country
     * @author Hassan Mehmood - I2L
     * @return \App\Models\Idea\Country
     */
    public function findOne()
    {
        $country = $this->country::with("translations")->byId(request('country_id'))->first();
        if (!$country) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        return $country;
    }
}
