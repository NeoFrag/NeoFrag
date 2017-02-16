<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 * @author: Michaël Bilcot <michael.bilcot@neofrag.com>
 */

namespace SocialConnect\OAuth2\Provider;

use SocialConnect\Provider\AccessTokenInterface;
use SocialConnect\Provider\Exception\InvalidAccessToken;
use SocialConnect\Provider\Exception\InvalidResponse;
use SocialConnect\OAuth2\AbstractProvider;
use SocialConnect\OAuth2\AccessToken;
use SocialConnect\Common\Http\Client\Client;

class BattleNet extends AbstractProvider
{
    public function getBaseUri()
    {
        return 'https://eu.api.battle.net/';
    }

    public function getAuthorizeUri()
    {
        return 'https://eu.battle.net/oauth/authorize';
    }

    public function getRequestTokenUri()
    {
        return 'https://eu.battle.net/oauth/token';
    }

    public function getName()
    {
        return 'battle-net';
    }

    /**
     * {@inheritdoc}
     */
    public function parseToken($body)
    {
        $result = json_decode($body, true);
        if ($result) {
            return new AccessToken($result);
        }

        throw new InvalidAccessToken('Provider response with not valid JSON');
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity(AccessTokenInterface $accessToken)
    {
        $response = $this->httpClient->request(
            $this->getBaseUri() . 'account/user',
            [
                'access_token' => $accessToken->getToken()
            ]
        );

        if (!$response->isSuccess()) {
            throw new InvalidResponse(
                'API response with error code',
                $response
            );
        }

        $body = $response->getBody();
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
