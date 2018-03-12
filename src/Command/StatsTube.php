<?php
    /**
     * @Author : a.zinovyev
     * @Package: beansclient
     * @License: http://www.opensource.org/licenses/mit-license.php
     */

    namespace xobotyi\beansclient\Command;

    use xobotyi\beansclient\Exception;
    use xobotyi\beansclient\Interfaces;
    use xobotyi\beansclient\Response;

    class StatsTube extends CommandAbstract
    {
        private $tube;

        public
        function __construct(string $tube) {
            if (!($tube = trim($tube))) {
                throw new Exception\Command('Tube name should be a valueable string');
            }

            $this->commandName = Interfaces\Command::STATS_TUBE;

            $this->tube        = $tube;
        }

        public
        function getCommandStr() :string {
            return $this->commandName . ' ' . $this->tube;
        }

        public
        function parseResponse(array $reponseHeader, ?string $reponseStr) {
            if ($reponseHeader[0] !== Response::OK) {
                throw new Exception\Command("Got unexpected status code [${reponseHeader[0]}]");
            }
            else if (!$reponseStr) {
                throw new Exception\Command('Got unexpected empty response');
            }

            // ToDo: make handle of NOT_FOUND status

            return Response::YamlParse($reponseStr, true);
        }
    }