<?php


namespace xobotyi\beansclient;

use PHPUnit\Framework\TestCase;
use xobotyi\beansclient\Command\Peek;
use xobotyi\beansclient\Exception\CommandException;
use xobotyi\beansclient\Serializer\JsonSerializer;

class PeekTest extends TestCase
{
    const HOST    = 'localhost';
    const PORT    = 11300;
    const TIMEOUT = 2;

    private function getConnection(bool $active = true) {
        $conn = $this->getMockBuilder(Connection::class)
                     ->disableOriginalConstructor()
                     ->getMock();

        $conn->expects($this->any())
             ->method('isActive')
             ->will($this->returnValue($active));

        return $conn;
    }

    // test if response has wrong status name

    public function testPeek() :void {
        $conn = $this->getConnection();

        $conn->method('readln')
             ->withConsecutive()
             ->willReturnOnConsecutiveCalls("NOT_FOUND", "FOUND 1 9", "FOUND 1 9");
        $conn->method('read')
             ->withConsecutive([9], [2], [9], [2])
             ->willReturnOnConsecutiveCalls("[1,2,3,4]", "\r\n", "[1,2,3,4]", "\r\n");

        $client = new BeansClient($conn);

        self::assertEquals(null, $client->peek(Peek::TYPE_BURIED));
        self::assertEquals(['id' => 1, 'payload' => '[1,2,3,4]'], $client->peek(1));

        $client = new BeansClient($conn, new JsonSerializer());
        self::assertEquals(['id' => 1, 'payload' => [1, 2, 3, 4]], $client->peek(1));
    }

    // test if response has no data in

    public function testPeekException1() :void {
        $conn = $this->getConnection();

        $conn->method('readln')
             ->will($this->returnValue("SOME_STUFF"));

        $client = new BeansClient($conn);

        $this->expectException(CommandException::class);
        $client->peek(123);
    }

    // test if jobId <= 0

    public function testPeekException2() :void {
        $conn = $this->getConnection();

        $conn->method('readln')
             ->will($this->returnValue("FOUND 0"));

        $conn->method('read')
             ->withConsecutive([0], [2])
             ->willReturnOnConsecutiveCalls("", "\r\n");

        $client = new BeansClient($conn);

        $this->expectException(CommandException::class);
        $client->peek(123);
    }

    // test if subject is unknown <= 0

    public function testPeekException3() :void {
        $conn = $this->getConnection();

        $conn->method('readln')
             ->will($this->returnValue("TOUCHED"));

        $client = new BeansClient($conn);

        $this->expectException(CommandException::class);
        $client->peek(0);
    }

    public function testPeekException4() :void {
        $conn = $this->getConnection();

        $client = new BeansClient($conn);

        $this->expectException(CommandException::class);
        $client->peek('stuff');
    }
}
