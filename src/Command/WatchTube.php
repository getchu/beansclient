<?php


namespace xobotyi\beansclient\Command;

use xobotyi\beansclient\Exception;
use xobotyi\beansclient\Interfaces;
use xobotyi\beansclient\Response;

/**
 * Class WatchTube
 *
 * @package xobotyi\beansclient\Command
 */
class WatchTube extends CommandAbstract
{
    /**
     * @var string
     */
    private $tube;

    /**
     * WatchTube constructor.
     *
     * @param string $tube
     *
     * @throws \xobotyi\beansclient\Exception\CommandException
     */
    public function __construct(string $tube) {
        if (!($tube = trim($tube))) {
            throw new Exception\CommandException('Tube name must be a valuable string');
        }

        $this->commandName = Interfaces\CommandInterface::WATCH;

        $this->tube = $tube;
    }

    /**
     * @return string
     */
    public function getCommandStr() :string {
        return $this->commandName . ' ' . $this->tube;
    }

    /**
     * @param array       $responseHeader
     * @param null|string $responseStr
     *
     * @return string
     * @throws \xobotyi\beansclient\Exception\CommandException
     */
    public function parseResponse(array $responseHeader, ?string $responseStr) :string {
        if ($responseStr) {
            throw new Exception\CommandException("Unexpected response data passed");
        }
        else if ($responseHeader[0] == Response::WATCHING) {
            return (int)$responseHeader[1];
        }

        throw new Exception\CommandException("Got unexpected status code [${responseHeader[0]}]");
    }
}