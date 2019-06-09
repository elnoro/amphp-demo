<?php
declare(strict_types=1);

namespace App\Logging;

use Amp\ByteStream\ResourceOutputStream;
use Amp\Log\ConsoleFormatter;
use Amp\Log\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class LoggerFactory
{
    public static function create(string $name): LoggerInterface
    {
        $handler = new StreamHandler(new ResourceOutputStream(\STDOUT));
        $handler->setFormatter(new ConsoleFormatter());
        $logger = new Logger($name);
        $logger->pushHandler($handler);

        return $logger;
    }
}
