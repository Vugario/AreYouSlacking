<?php

namespace App\Library\OAuth2\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;

class Slack extends AbstractProvider
{
    public $scopes = ['identify', 'read', 'post', 'client'];

    public function urlAuthorize()
    {
        return 'https://slack.com/oauth/authorize';
    }

    public function urlAccessToken()
    {
        return 'https://slack.com/api/oauth.access';
    }

    public function urlUserDetails(\League\OAuth2\Client\Token\AccessToken $token)
    {
        return 'https://slack.com/api/auth.test?token='.$token;
    }

    public function urlUserAdditionalDetails(\League\OAuth2\Client\Token\AccessToken $token, $user)
    {
        return 'https://slack.com/api/users.info?token='.$token.'&user='.$user;
    }

    public function userDetails($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
        // Fetch initial information
        $additionalDetails = json_decode($this->fetchProviderData($this->urlUserAdditionalDetails($token, $response->user_id)));

        return [
            'user_id'       => $response->user_id,
            'team_id'       => $response->team_id,
            'team'          => $response->team,
            'name'          => $additionalDetails->user->profile->real_name,
            'firstname'     => $additionalDetails->user->profile->first_name,
            'lastname'      => $additionalDetails->user->profile->last_name,
            'email'         => $additionalDetails->user->profile->email,
            'image'         => $additionalDetails->user->profile->image_192
        ];
    }

    public function userUid($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
        return $response->id;
    }

    public function userEmail($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
        return isset($response->email) && $response->email ? $response->email : null;
    }

    public function userScreenName($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
        return [$response->first_name, $response->last_name];
    }
}
