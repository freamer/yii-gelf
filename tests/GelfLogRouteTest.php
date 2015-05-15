<?php
namespace Bankiru\Yii\Logging\Graylog2;

class GelfLogRouteTest extends \PHPUnit_Framework_TestCase
{

    public function testProcessLogs()
    {
        $instance = new GelfLogRoute();
        $instance->extra = ['extra_field' => 1];
        $instance->init();

        /** @var \PHPUnit_Framework_MockObject_MockObject|\Gelf\Transport\TransportInterface $mockTransport */
        $mockTransport = $this->getMock('Gelf\\Transport\\TransportInterface');
        $mockTransport
            ->expects(static::exactly(3))
            ->method('send')
            ->with(static::isInstanceOf('Gelf\\MessageInterface'))
            ->willReturn(100500);
        $instance->setTransport($mockTransport);

        $refMethod = (new \ReflectionClass($instance))->getMethod('processLogs');
        $refMethod->setAccessible(true);
        $refMethod->invoke($instance, [
            ['test message 1', 'trace', 'category', microtime(true)],
            [['message' => 'test message 2', 'extra_field_2' => 2], 'trace', 'category', microtime(true), [['file' => 'path/to/somefile', 'line' => 13]]],
            [(object)['message'=>'test message 1'], 'trace', 'category', microtime(true)],
        ]);
    }
}
