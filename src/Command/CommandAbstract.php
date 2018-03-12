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

    abstract
    class CommandAbstract implements Interfaces\Command
    {
        protected $payload;
        protected $payloadEncoder;

        protected $commandName;

        public
        function __toString() :string {
            return $this->getCommandStr();
        }

        public
        function hasPayload() :bool {
            return (bool)$this->payload;
        }

        public
        function getPayload() {
            return $this->payload;
        }

        public
        function setPayloadEncoder(?Interfaces\Encoder $encoder) :self {
            $this->payloadEncoder = $encoder;

            return $this;
        }

        public
        function parseResponse(array $reponseHeader, ?string $reponseStr) {
            if ($reponseHeader[0] !== Response::OK) {
                throw new Exception\Command("Got unexpected status code [${reponseHeader[0]}]");
            }
            else if (!$reponseStr) {
                throw new Exception\Command('Got unexpected empty response');
            }

            return Response::YamlParse($reponseStr);
        }
    }