<?php

/*
 * This file is part of the IdeaToLife package.
 * This is a middleware that ensure following things:
 * 1. First the incoming request has a device_type and version.
 * 2. Secondly the incoming version is match from the database or not
 *
 * (c) Muhammad Abid <muhammad.abd@ideatolife.me>
 *
 */
namespace App\Idea\Http\Middleware;

use Closure;
use App\Idea\Types\ExceptionType;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Idea\Http\Response\BaseResponse;
use App\Models\Idea\AppVersion;

//hassan, I didn't get exactly the purpose behind this, can you check with Muhammad Abid or Shuja
class AppVersionMiddleware
{
    use ExceptionType;
    public $request;


    /**
     * Create a new BaseMiddleware instance.
     * @param Idea\Models\AppVersion
     * @return void
     */
    public function __construct(AppVersion $appVersionModel)
    {
        $this->appVersionModel = $appVersionModel;
    }

    /**
     * Description: The following method is used to check the incoming app version from DB
     *
     * @author Muhammad Abid - I2L
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function handle(Request $request, Closure $next)
    {
        $errors = [];
        // $response = new BaseResponse();
        $this->request = $request;
        $deviceType = $request->headers->get('device_type');
        $appVersion = $request->headers->get('version');

        if (!isset($deviceType) || !isset($appVersion)) {
            !empty($deviceType) ?: $errors[] = 'general.no_device_type_found';
            !empty($appVersion) ?: $errors[] = 'general.no_app_version_found';

            $this->raiseInvalidRequestException('', $errors);
        }

        $version = $this->appVersionModel->where('device_type', $deviceType)
                                            ->where('version', $appVersion)
                                            ->where('active', 1)
                                            ->first();
        $response = $next($request);
        if (!$version) {
            // get the latest version for the respective device type
            $latestVersion = $this->getLatestVersion($deviceType);

            // if $latestVersion is empty throw a error
            if (empty($latestVersion)) {
                $this->raiseInvalidRequestException('', ['general.invalid_device_type']);
            }

            // set headers if the app version not match
            $response->headers->set('version', $latestVersion['version']);
            $response->headers->set('update_type', $latestVersion['update_type']);
        }

        return $response;
    }

    /**
     * get the latest version for the respective device type
     *
     * @param  $deviceType
     * @return array
     */
    private function getLatestVersion($deviceType)
    {
        $version = $this->appVersionModel->where('device_type', $deviceType)->where('active', '1')->first();
        $latestVersion = [];
        if ($version) {
            $latestVersion['update_type'] = $version->update_type;
            $latestVersion['version']     = $version->version;
        }
        return $latestVersion;
    }
}
