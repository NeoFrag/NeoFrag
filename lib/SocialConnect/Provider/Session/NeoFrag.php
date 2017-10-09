<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace SocialConnect\Provider\Session;

class NeoFrag implements SessionInterface
{
    private $_session;

    public function __construct($session)
    {
        $this->_session = $session;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return unserialize(serialize(call_user_func_array($this->_session, ['SocialConnect', $key])));
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->_session->set('SocialConnect', $key, $value);
    }

    /**
     * @param string $key
     */
    public function delete($key)
    {
        $this->_session->destroy('SocialConnect', $key);
    }
}
