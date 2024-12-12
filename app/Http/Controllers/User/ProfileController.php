<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/7/2017
 * Time: 2:39 PM
 */

namespace App\Http\Controllers\User;

use App\Idea\Base\BaseController;
use App\Models\Idea\Profile;
use App\Repositories\User\ProfileRepository;
use App\Repositories\User\UserAccountRepository;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends BaseController
{
    protected $userAccountRepository;
    protected $profileRepository;
    protected $imageService;
    protected $profileModel;

    /**
     * @param UserAccountRepository $userAccountRepository
     * @param ProfileRepository $profileRepository
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(UserAccountRepository $userAccountRepository, ProfileRepository $profileRepository, Request $request)
    {
        parent::__construct($request);

        //five request per second only
        $this->middleware('throttle:5,1');

        $this->userAccountRepository = $userAccountRepository;
        $this->profileRepository = $profileRepository;
    }

    /**
     * Validation Rules
     */
    protected static function validationRules()
    {
        return [
            'changePassword' => [
                'user_id' => 'required',
                'current_password' => 'required',
                'new_password' => 'required|min:6',
            ]
        ];
    }

    /**
     * Init
     */
    protected function init()
    {
        $this->setModel(new Profile());
        $this->with = ['country', 'user'];
        $this->withImage = true;
        $this->imageName = "image";
        $this->filePath = "uploads/{user_id}/profile_picture/";
        $this->imageService = new ImageService($this->withImage, $this->withImageThumb, $this->imageName, $this->thumbnailName, $this->filePath);
    }

    /**
     * Description: The following method will fetch all Profiles.
     * @return JsonResponse success
     */
    public function index()
    {
        return $this->successData($this->profileRepository->findAll());
    }

    /**
     * Description: The following method will fetch one Profile.
     *
     * @param int    : the profile id
     *
     * @return JsonResponse success or failure
     */
    public function one($id)
    {
        return $this->success('idea::general.general_data_fetch_message', $this->profileRepository->findOne($id));
    }

    /**
     * Description: The following method will delete User's Profile.
     *
     * @param int    : the profile id
     *
     * @return JsonResponse success or failure
     */
    public function destroy($id)
    {
        return $this->profileRepository->deleteProfile($id, $this->imageService) ? $this->success() : $this->failed();
    }

    /**
     * Function to edit profile of respected user
     *
     * @int user_id
     * @string other fields
     * @return JsonResponse
     */
    public function editProfileByUserId()
    {
        return $this->successData($this->profileRepository->updateByUserId($this->imageService));
    }

    /**
     * Function to return respected user
     *
     * @int user_id
     * @return JsonResponse
     */
    public function profileByUserId()
    {
        $profile = $this->profileRepository->findByUserId();
        return ($profile) ? $this->successData($profile) : $this->failed('idea::general.record_does_not_exist');
    }

    /**
     * Function to change password
     *
     * @int user_id
     * @string current_password
     * @string new_password
     * @return JsonResponse
     */
    public function changePassword()
    {
        return $this->success('idea::general.update_success', $this->profileRepository->updateUserPassword());
    }
}
