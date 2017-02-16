<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace SocialConnect\OAuth2\Provider;

use SocialConnect\Provider\AccessTokenInterface;
use SocialConnect\Provider\Exception\InvalidResponse;
use SocialConnect\Common\Http\Client\Client;

class Facebook extends \SocialConnect\OAuth2\AbstractProvider
{
    /**
     * By default AbstractProvider use POST method, FB does not accept POST and return HTML page ᕙ(⇀‸↼‶)ᕗ
     *
     * @var string
     */
    protected $requestHttpMethod = Client::GET;

    public function getBaseUri()
    {
        return 'https://graph.facebook.com/v2.8/';
    }

    public function getAuthorizeUri()
    {
        return 'https://www.facebook.com/dialog/oauth';
    }

    public function getRequestTokenUri()
    {
        return 'https://graph.facebook.com/oauth/access_token';
    }

    public function getName()
    {
        return 'facebook';
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity(AccessTokenInterface $accessToken)
    {
        $response = $this->httpClient->request(
            $this->getBaseUri() . 'me',
            [
                'access_token' => $accessToken->getToken(),
                'fields' => implode(',', [
                    'email',
                    'first_name',
                    'last_name',
                    'gender',
                    'locale'
                ])
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
