<?php
/**
 * @Author : a.zinovyev
 * @Package: beansclient
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

namespace xobotyi\beansclient;


/**
 * Class SocketFunctions
 *
 * @package xobotyi\beansclient
 */
class SocketFunctions
{
    /**
     * @param $stream
     *
     * @return bool
     */
    public function fclose($stream) :bool {
        return fclose($stream);
    }

    /**
     * @param $stream
     *
     * @return bool
     */
    public function feof($stream) :bool {
        return feof($stream);
    }

    /**
     * @param          $stream
     * @param int|null $length
     *
     * @return bool|string
     */
    public function fgets($stream, int $length = null) {
        if ($length !== null) {
            return fgets($stream, $length);
        }

        return fgets($stream);
    }

    /**
     * @param     $stream
     * @param int $length
     *
     * @return bool|string
     */
    public function fread($stream, int $length) {
        return fread($stream, $length);
    }

    /**
     * @param string $hostName
     * @param int    $port
     * @param int    $errNo
     * @param string $errStr
     * @param float  $timeout
     *
     * @return bool|null|resource
     */
    public function fsockopen(string $hostName, int $port = -1, &$errNo = null, &$errStr = null, float $timeout = null) {
        return @fsockopen($hostName, $port, $errNo, $errStr, $timeout);
    }

    /**
     * @param          $stream
     * @param string   $text
     * @param int|null $length
     *
     * @return bool|int|null
     */
    public function fwrite($stream, string $text, int $length = null) {
        if ($length !== null) {
            return fwrite($stream, $text, $length);
        }

        return fwrite($stream, $text);
    }

    /**
     * @param string $hostName
     * @param int    $port
     * @param int    $errNo
     * @param string $errStr
     * @param float  $timeout
     *
     * @return bool|null|resource
     */
    public function pfsockopen(string $hostName, int $port = -1, &$errNo = null, &$errStr = null, float $timeout = null) {
        return @pfsockopen($hostName, $port, $errNo, $errStr, $timeout);
    }

    /**
     * @param     $stream
     * @param int $seconds
     * @param int $microseconds
     *
     * @return bool
     */
    public function setReadTimeout($stream, int $seconds, int $microseconds = 0) :bool {
        return stream_set_timeout($stream, $seconds, $microseconds);
    }
}
