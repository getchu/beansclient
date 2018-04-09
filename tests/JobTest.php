<?php
/**
 * @Author : a.zinovyev
 * @Package: beansclient
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

namespace xobotyi\beansclient;


use PHPUnit\Framework\Error\Notice;
use PHPUnit\Framework\TestCase;

class JobTest extends TestCase
{

    public function testException1() {
        $client = $this->getClient(false);

        $this->expectException(Exception\Job::class);
        $job = new Job($client, 1, Job::STATE_READY, '12345');
    }

    public function testNotice() {
        $client = $this->getClient();

        $job = new Job($client, 1, Job::STATE_READY, '12345');

        Notice::$enabled = false;
        $this->assertEquals(null, @$job->dfgjhdkfjg);

        Notice::$enabled = true;
        $this->expectException(Notice::class);
        $this->assertEquals(null, $job->dfgjhdkfjg);
    }

    public function test__get() {
        $client = $this->getClient();

        $client->method('statsJob')
               ->withConsecutive()
               ->willReturnOnConsecutiveCalls(
                   $this->returnValue([
                                          'pri' => 2048,
                                      ]),
                   false,
                   ['state' => 'delayed', 'time-left' => 0], ['state' => 'ready',]
               );

        $client->method('peek')
               ->will($this->returnValue(
                   [
                       'id'      => 123,
                       'payload' => '321',
                   ]));

        $job = new Job($client, 1, Job::STATE_READY);

        $this->assertEquals(2048, $job->priority);
        $this->assertEquals('321', $job->payload);
        $this->assertEquals(1, $job->id);

        $job = new Job($client, 1);

        $this->assertEquals(Job::STATE_DELETED, $job->state);

        $job = new Job($client, 1);

        $this->assertEquals(Job::STATE_READY, $job->state);
    }

    public function testIsDelayed() {
        $client = $this->getClient();

        $job = new Job($client, 1, Job::STATE_DELAYED, '12345');

        $this->assertTrue($job->isDelayed());
    }

    public function testIsBuried() {
        $client = $this->getClient();

        $job = new Job($client, 1, Job::STATE_BURIED, '12345');

        $this->assertTrue($job->isBuried());
    }

    public function testIsReserved() {
        $client = $this->getClient();
        $job    = new Job($client, 1, Job::STATE_RESERVED, '12345');

        $this->assertTrue($job->isReserved());
    }

    public function testIsDeleted() {
        $client = $this->getClient();

        $job = new Job($client, 1, Job::STATE_DELETED, '12345');

        $this->assertTrue($job->isDeleted());
    }

    public function testIsReady() {
        $client = $this->getClient();

        $job = new Job($client, 1, Job::STATE_READY, '12345');

        $this->assertTrue($job->isReady());
    }

    public function testGetClient() {
        $client = $this->getClient();

        $job = new Job($client, 1, Job::STATE_READY, '12345');
        $this->assertEquals($client, $job->getClient());
    }

    public function testSetClient() {
        $client1 = $this->getClient();
        $client2 = $this->getClient();

        $job = new Job($client1, 1, Job::STATE_READY, '12345');
        $this->assertEquals($client2, $job->setClient($client2)->getClient());
    }

    public function testGetData() {
        $client = $this->getClient();

        $client->method('statsJob')
               ->willReturn(['state' => 'ready', 'time-left' => 0]);

        $client->method('peek')
               ->will($this->returnValue(
                   [
                       'id'      => 123,
                       'payload' => '321',
                   ]));

        $job = new Job($client, 1);

        self::assertEquals([
                               'id'          => 1,
                               'payload'     => '321',
                               'tube'        => null,
                               'state'       => 'ready',
                               'priority'    => null,
                               'age'         => null,
                               'delay'       => null,
                               'ttr'         => null,
                               'timeLeft'    => 0,
                               'releaseTime' => 0,
                               'file'        => null,
                               'reserves'    => null,
                               'timeouts'    => null,
                               'releases'    => null,
                               'buries'      => null,
                               'kicks'       => null,
                           ],
                           $job->getData());
    }

    public function testKick() {
        $client = $this->getClient();


        $client->method('statsJob')
               ->willReturn(['state' => 'ready', 'time-left' => 0]);

        $client->method('kickJob')
               ->willReturn(true);

        $job = new Job($client, 1);

        self::assertEquals('ready', $job->kick()->state);
    }

    public function testTouch() {
        $client = $this->getClient();


        $client->method('statsJob')
               ->willReturn(['state' => 'ready', 'time-left' => 0]);

        $client->method('touch')
               ->withConsecutive()
               ->willReturnOnConsecutiveCalls(true, false);

        $job = new Job($client, 1);

        self::assertEquals('ready', $job->touch()->state);
        self::assertEquals('ready', $job->touch()->state);
    }

    //    public function testRelease() {
    //
    //    }
    //
    //    public function testBury() {
    //
    //    }
    //
    //    public function testDelete() {
    //
    //    }

    private function getClient(bool $activeConnection = true) {
        $conn = $this->getMockBuilder('\xobotyi\beansclient\Connection')
                     ->disableOriginalConstructor()
                     ->getMock();

        if (!$activeConnection) {
            $conn->method('isActive')
                 ->withConsecutive()
                 ->willReturnOnConsecutiveCalls(true, $activeConnection);
        }
        else {
            $conn->method('isActive')
                 ->willReturn(true);
        }

        $client = $this->getMockBuilder('\xobotyi\beansclient\BeansClient')
                       ->setMethods(['statsJob', 'peek', 'kickJob', 'touch'])
                       ->setConstructorArgs([&$conn])
                       ->getMock();

        return $client;
    }
}