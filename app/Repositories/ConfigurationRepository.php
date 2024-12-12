<?php


namespace App\Repositories;

use App\Idea\Base\BasePaging;
use App\Models\Idea\Configuration;
use App\Idea\Types\ExceptionType;
use App\Models\Idea\Country;
use Illuminate\Http\Request;

/**
 * Description: The following repository is used to handle all function related to configurations
 * Class UserAccountRepository
 * @package App\Repositories\User
 */
class ConfigurationRepository
{
    use ExceptionType;

    protected $configuration;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Configuration $configuration, Request $request)
    {
        $this->request = $request;
        $this->configuration = $configuration;
    }

    /**
     * Description: This function will return all configurations
     * @author Hassan Mehmood - I2L
     */
    public function findAll()
    {
        return $this->configuration::all();
    }

    /**
     * Description: This function will update all configurations
     * @author Hassan Mehmood - I2L
     * @return boolean
     */
    public function updateAllConfiguration()
    {
        $data = $this->request->all();
        if (!empty($data)) {
            foreach ($data as $conf) {
                if (isset($conf['id'])) {
                    $configuration = $this->configuration::byID($conf['id'])->first();
                    $configuration->value = $conf['value'];
                    $configuration->save();
                }
            }
        }

        return true;
    }
}
