<?php


namespace App\Repositories\Role;

use App\Models\Idea\Action;
use App\Idea\Types\ExceptionType;

/**
 * Description: The following repository is used to handle all function related to actions
 * Class UserAccountRepository
 * @package App\Repositories\User
 */
class ActionRepository
{
    use ExceptionType;

    protected $action;

    public function __construct(Action $action)
    {
        $this->action = $action;
    }

    /**
     * Description: This function returns all actions
     * @author Hassan Mehmood - I2L
     * @return \App\Models\Idea\Action
     */
    public function findAll()
    {
        $query = $this->action::all();
        return $query;
    }
}
