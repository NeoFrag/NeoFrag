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

class LinkedIn extends AbstractProvider
{
    public function getBaseUri()
    {
        return 'https://api.linkedin.com/v1/';
    }

    public function getAuthorizeUri()
    {
        return 'https://www.linkedin.com/oauth/v2/authorization';
    }

    public function getRequestTokenUri()
    {
        return 'https://www.linkedin.com/oauth/v2/accessToken';
    }

    public function getName()
    {
        return 'linkedin';
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
            $this->getBaseUri() . 'people/~:(id,first-name,last-name,picture-url,email-address)',
            [
                'oauth2_access_token' => $accessToken->getToken(),
                'format' => 'json'
            ]
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
