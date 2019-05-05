<?php
/**
 * @Author : a.zinovyev
 * @Package: beansclient
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

namespace xobotyi\beansclient\Serializer;


use xobotyi\beansclient\Interfaces\SerializerInterface;

/**
 * Class Json
 *
 * @package xobotyi\beansclient\Serializer
 */
class JsonSerializer implements SerializerInterface
{
    /**
     * @param $data
     *
     * @return string
     */
    public
    function serialize($data) :string {
        return json_encode($data);
    }

    /**
     * @param string $str
     *
     * @return mixed
     */
    public
    function unserialize(string $str) {
        return json_decode($str, true);
    }
}