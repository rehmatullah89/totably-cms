<?php

/*
 * This file is part of the IdeaToLife package.
 *
 * (c) Youssef Jradeh <youssef.jradeh@ideatolife.me>
 *
 */

namespace App\Idea\Types;

use App\Models\Idea\UserProviderToken;

/**
 * Eloquent Model Base class
 */
trait SocialType
{
    /*
     * facebook validation
     * validate facebook token
     */
    public function validateFbToken($fbUserId, $accessToken)
    {
        try {
            $appId  = env("FACEBOOK_ID");
            $secret = env("FACEBOOK_SECRET");

            $fb = new \Facebook\Facebook(['app_id' => $appId, 'app_secret' => $secret]);

            // The OAuth 2.0 client handler helps us manage access tokens
            $oAuth2Client = $fb->getOAuth2Client();

            // Get the access token metadata from /debug_token
            $tokenMetadata = $oAuth2Client->debugToken($accessToken);

            // Validation (these will throw FacebookSDKException's when they fail)
            $tokenMetadata->validateAppId($appId);

            // If you know the user ID this access token belongs to, you can validate it here
            $tokenMetadata->validateUserId($fbUserId);
            $tokenMetadata->validateExpiration();

            // Exchanges a short-lived access token for a long-lived one
            return $oAuth2Client->getLongLivedAccessToken($accessToken);
        } catch (\Exception $exc) {
            $this->raiseHttpResponseException($exc->getMessage());
        }
    }


    /**
     * @param $user
     * @param $fbUserId
     * @param $longTimeToken
     */
    protected function toggleUserToken($userId, $fbUserId, $longTimeToken, $from = 'facebook')
    {
        $token = UserProviderToken::firstOrNew(['from' => $from, 'user_id' => $userId]);

        $token->token_id    = $fbUserId;
        $token->token_value = $longTimeToken;
        $token->expiry_date = $longTimeToken->getExpiresAt();

        $token->save();
    }
}
