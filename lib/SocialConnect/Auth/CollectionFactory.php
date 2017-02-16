<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace SocialConnect\Auth;

use LogicException;
use SocialConnect\Provider\AbstractBaseProvider;
use SocialConnect\Provider\Consumer;
use SocialConnect\OAuth1;
use SocialConnect\OAuth2;
use SocialConnect\OpenID;

/**
 * Class Factory
 * @package SocialConnect\Auth\Provider
 */
class CollectionFactory implements FactoryInterface
{
    /**
     * @var array
     */
    protected $providers = [
        // OAuth1
        'twitter'       => 'SocialConnect\OAuth1\Provider\Twitter',
        'px500'         => 'SocialConnect\OAuth1\Provider\Px500',
        'tumblr'        => 'SocialConnect\OAuth1\Provider\Tumblr',
        // OAuth2
        'facebook'      => 'SocialConnect\OAuth2\Provider\Facebook',
        'github'        => 'SocialConnect\OAuth2\Provider\GitHub',
        'instagram'     => 'SocialConnect\OAuth2\Provider\Instagram',
        'google'        => 'SocialConnect\OAuth2\Provider\Google',
        'vk'            => 'SocialConnect\OAuth2\Provider\Vk',
        'slack'         => 'SocialConnect\OAuth2\Provider\Slack',
        'twitch'        => 'SocialConnect\OAuth2\Provider\Twitch',
        'bitbucket'     => 'SocialConnect\OAuth2\Provider\Bitbucket',
        'amazon'        => 'SocialConnect\OAuth2\Provider\Amazon',
        'gitlab'        => 'SocialConnect\OAuth2\Provider\GitLab',
        'vimeo'         => 'SocialConnect\OAuth2\Provider\Vimeo',
        'digital-ocean' => 'SocialConnect\OAuth2\Provider\DigitalOcean',
        'yandex'        => 'SocialConnect\OAuth2\Provider\Yandex',
        'mail-ru'       => 'SocialConnect\OAuth2\Provider\MailRu',
        'odnoklassniki' => 'SocialConnect\OAuth2\Provider\Odnoklassniki',
        'linkedin'      => 'SocialConnect\OAuth2\Provider\LinkedIn',
        'battle-net'    => 'SocialConnect\OAuth2\Provider\BattleNet',
        // OpenID
        'steam'         => 'SocialConnect\OpenID\Provider\Steam',
    ];

    /**
     * @param array $providers
     */
    public function __construct(array $providers = null)
    {
        if ($providers) {
            $this->providers = $providers;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->providers[$id]);
    }

    /**
     * @param string $id
     * @param array $parameters
     * @param Service $service
     * @return \SocialConnect\Provider\AbstractBaseProvider
     */
    public function factory($id, array $parameters, Service $service)
    {
        $consumer = new Consumer($parameters['applicationId'], $parameters['applicationSecret']);

        if (isset($parameters['applicationPublic'])) {
            $consumer->setPublic($parameters['applicationPublic']);
        }

        $id = strtolower($id);

        if (!isset($this->providers[$id])) {
            throw new LogicException('Provider with $id = ' . $id . ' doest not exist');
        }

        $providerClassName = $this->providers[$id];

        /**
         * @var $provider \SocialConnect\Provider\AbstractBaseProvider
         */
        $provider = new $providerClassName(
            $service->getHttpClient(),
            $service->getSession(),
            $consumer,
            array_merge(
                $parameters,
                $service->getConfig()
            )
        );

        return $provider;
    }

    /**
     * Register new provider to Provider's collection
     *
     * @param AbstractBaseProvider $provider
     */
    public function register(AbstractBaseProvider $provider)
    {
        $this->providers[$provider->getName()] = get_class($provider);
    }
}
