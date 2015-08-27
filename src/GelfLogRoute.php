<?php
namespace Freamer\Yii\Logging\Graylog2;

use Gelf;
use Psr\Log\LogLevel;

class GelfLogRoute extends \CLogRoute
{
    /** @var string Graylog2 host */
    public $host = '127.0.0.1';
    /** @var integer Graylog2 port */
    public $port = 12201;
    /** @var int Graylog2 chunk size */
    public $chunkSize = Gelf\Transport\UdpTransport::CHUNK_SIZE_LAN;
    /** @var array default additional params */
    public $extra = [];

    /** @var array graylog levels */
    private $_levels = [
        \CLogger::LEVEL_TRACE   => LogLevel::DEBUG,
        \CLogger::LEVEL_PROFILE => LogLevel::DEBUG,
        \CLogger::LEVEL_INFO    => LogLevel::INFO,
        \CLogger::LEVEL_WARNING => LogLevel::WARNING,
        \CLogger::LEVEL_ERROR   => LogLevel::ERROR,
    ];

    /** @var Gelf\Transport\TransportInterface */
    private $transport;

    public function init()
    {
        $this->setTransport(new Gelf\Transport\UdpTransport($this->host, $this->port, $this->chunkSize));
    }

    public function setTransport(Gelf\Transport\TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Processes log messages and sends them to specific destination.
     * Derived child classes must implement this method.
     * @param array $logs list of messages. Each array element represents one message
     * with the following structure:
     * array(
     *   [0] => message (string)
     *   [1] => level (string)
     *   [2] => category (string)
     *   [3] => timestamp (float, obtained by microtime(true));
     */
    protected function processLogs($logs)
    {
        $publisher = new Gelf\Publisher($this->transport);

        foreach ($logs as $logItem) {
            list($message, $level, $category, $timestamp) = $logItem;
            $gelfMessage = new Gelf\Message;

            if (is_string($message)) {
                $gelfMessage->setShortMessage($message);
            } elseif (!is_array($message)) {
                $gelfMessage->setShortMessage(var_export($message, true));
            } else {
                if (isset($message['message'])) {
                    $gelfMessage->setShortMessage($message['message']);
                    unset($message['message']);
                    foreach ($message as $key => $val) {
                        if (is_string($key)) {
                            $gelfMessage->setAdditional($key, is_string($val) ? $val : var_export($val, true));
                        }
                    }
                } else {
                    $gelfMessage->setShortMessage(var_export($message, true));
                }
            }

            $gelfMessage
                ->setLevel(($_ = &$this->_levels[$level]) ?: LogLevel::INFO)
                ->setTimestamp($timestamp)
                ->setFacility($category)
                ->setAdditional('level_name', strtolower($level));

            foreach ($this->extra as $key => $val) {
                if (is_string($key)) {
                    $gelfMessage->setAdditional($key, is_string($val) ? $val : var_export($val, true));
                }
            }

            if (isset($logItem[4]) && is_array($logItem[4])) {
                $traces = [];

                foreach ($logItem[4] as $index => $trace) {
                    $traces[] = "{$trace['file']}:{$trace['line']}";
                    if (0 === $index) {
                        $gelfMessage->setFile($trace['file']);
                        $gelfMessage->setLine($trace['line']);
                    }
                }

                $gelfMessage->setAdditional('trace', implode("\n", $traces));
            }

            // Publishing message
            $publisher->publish($gelfMessage);
        }
    }
}
