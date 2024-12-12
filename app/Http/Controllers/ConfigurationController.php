<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 5/16/2017
 * Time: 10:45 AM
 */

namespace App\Http\Controllers;

use App\Idea\Base\BaseController;
use App\Models\Idea\Configuration;
use App\Repositories\ConfigurationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConfigurationController extends BaseController
{
    protected $permissions = [
        "index" => ["code" => "configuration", "action" => "read"],
        "destroy" => ["code" => "configuration", "action" => "write"],
        "store" => ["code" => "configuration", "action" => "write"],
        "update" => ["code" => "configuration", "action" => "write"],
        "updateAll" => ["code" => "configuration", "action" => "write"],
    ];

    protected $configurationRepository;

    /**
     * @param ConfigurationRepository $configurationRepository
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(ConfigurationRepository $configurationRepository, Request $request)
    {
        parent::__construct($request);
        $this->configurationRepository = $configurationRepository;
    }

    /**
     * Validation Rules
     */
    protected static function validationRules()
    {
        return [];
    }

    /**
     * Init
     */
    protected function init()
    {
        $this->setModel(new Configuration());
    }

    /**
     * Description: The following method will fetch all configurations.
     * @return JsonResponse success
     */
    public function index()
    {
        return $this->successData($this->configurationRepository->findAll());
    }

    /**
     * Function to update all configurations
     *
     * @return JsonResponse
     */
    public function updateAll()
    {
        return $this->success("idea::general.update_record_success", $this->configurationRepository->updateAllConfiguration());
    }
}
