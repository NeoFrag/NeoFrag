<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace SocialConnect\OAuth1\Provider;

use SocialConnect\Provider\AccessTokenInterface;
use SocialConnect\Provider\Exception\InvalidResponse;
use SocialConnect\Common\Http\Client\Client;

class Twitter extends \SocialConnect\OAuth1\AbstractProvider
{
    public function getBaseUri()
    {
        return 'https://api.twitter.com/1.1/';
    }

    public function getAuthorizeUri()
    {
        return 'https://api.twitter.com/oauth/authenticate';
    }

    public function getRequestTokenUri()
    {
        return 'https://api.twitter.com/oauth/request_token';
    }

    public function getRequestTokenAccessUri()
    {
        return 'https://api.twitter.com/oauth/access_token';
    }

    public function getName()
    {
        return 'twitter';
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity(AccessTokenInterface $accessToken)
    {
        $this->consumerToken = $accessToken;

        $parameters = [
            'oauth_consumer_key' => $this->consumer->getKey(),
            'oauth_token' => $accessToken->getToken(),
            'skip_status' => 1,
            'include_email' => 'true'
        ];

        $response = $this->oauthRequest(
            $this->getBaseUri() . 'account/verify_credentials.json',
            Client::GET,
            $parameters
        );

        if (!$response->isSuccess()) {
            throw new InvalidResponse(
                'API response with error code',
                $response
            );
        }

        $result = $response->json();
        if (!$result) {
            throw new InvalidResponse(
                'API response is not a valid JSON object',
                $response->getBody()
            );
        }

        return $result;
    }
}
