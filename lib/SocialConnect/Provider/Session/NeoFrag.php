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
        return unserialize(serialize(call_user_func_array($this->_session, ['auth', $key])));
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        call_user_func_array([$this->_session, 'set'], ['auth', $key, $value]);
    }

    /**
     * @param string $key
     */
    public function delete($key)
    {
        call_user_func_array([$this->_session, 'destroy'], ['auth', $key]);
    }
}
