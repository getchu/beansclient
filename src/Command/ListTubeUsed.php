<?php
    /**
     * @Author : a.zinovyev
     * @Package: beansclient
     * @License: http://www.opensource.org/licenses/mit-license.php
     */

    namespace xobotyi\beansclient\Command;

    use xobotyi\beansclient\Exception\Command;
    use xobotyi\beansclient\Interfaces;
    use xobotyi\beansclient\Response;

    class ListTubeUsed extends CommandAbstract
    {
        public
        function __construct() {
            $this->commandName = Interfaces\Command::LIST_TUBE_USED;
        }

        public
        function getCommandStr() :string {
            return $this->commandName;
        }

        public
        function parseResponse(array $reponseHeader, ?string $reponseStr) {
            if ($reponseHeader[0] !== Response::USING) {
                throw new Command("Got unexpected status code [${reponseHeader[0]}]");
            }

            return $reponseHeader[1];
        }
    }