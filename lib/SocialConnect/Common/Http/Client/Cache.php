<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace SocialConnect\Common\Http\Client;

class Cache implements ClientInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $cache;

    /**
     * @var int
     */
    protected $lifetime;

    /**
     * @param Client $client
     * @param \Doctrine\Common\Cache\Cache $cache
     * @param int $lifetime
     */
    public function __construct(Client $client, \Doctrine\Common\Cache\Cache $cache, $lifetime = 3600)
    {
        $this->client = $client;
        $this->cache = $cache;
        $this->lifetime = (int) $lifetime;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $parameters
     * @return string|null
     */
    protected function makeCacheKey($method, $url, array $parameters = array())
    {
        if ($method != Client::GET) {
            return null;
        }

        $cacheKey = $url;

        if ($parameters) {
            foreach ($parameters as $key => $value) {
                $cacheKey .= $key . '-' . $value;
            }
        }

        return $cacheKey;
    }

    public function request($url, array $parameters = array(), $method = Client::GET, array $headers = array(), array $options = array())
    {
        $key = $this->makeCacheKey($method, $url, $parameters);
        if ($key) {
            if ($this->cache->contains($key)) {
                return $this->cache->fetch($key);
            }
        }

        $result = $this->client->request($url, $parameters, $method, $headers, $options);
        if ($key) {
            $this->cache->save($key, $result, $this->lifetime);
        }

        return $result;
    }
}
