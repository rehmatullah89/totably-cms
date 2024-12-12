<?php
/**
 * CountryController
 *
 * (c) Hassan Mehmood <hassan.mehmood@ideatolife.me>
 *
 */

namespace App\Http\Controllers;

use App\Idea\Base\BaseController;
use App\Repositories\CountryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends BaseController
{
    protected $countryRepository;

    /**
     * @param CountryRepository $countryRepository
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(CountryRepository $countryRepository, Request $request)
    {
        parent::__construct($request);
        $this->countryRepository = $countryRepository;
    }

    /**
     * Validation Rules
     */
    protected static function validationRules()
    {
        return [
        ];
    }

    /**
     * Function to return all countries
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->successData($this->countryRepository->findAll());
    }

    /**
     * Function to return respective country
     *
     * @return JsonResponse
     */
    public function one()
    {
        return $this->successData($this->countryRepository->findOne());
    }
}
