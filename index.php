<?php

require 'vendor/autoload.php';

use Amp\Http\Server\RequestHandler\CallableRequestHandler;
use Amp\Http\Server\Router;
use Amp\Http\Server\Server;
use Amp\Socket;
use App\Links\InMemoryLinkRepository;
use App\RequestHandler\LinkRenderer;
use App\Logging\LoggerFactory;
use App\RequestHandler\LinkController;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;

Amp\Loop::run(function () {
    $port = getenv('WEB_APP_PORT');
    $sockets = [
        Socket\listen("0.0.0.0:{$port}"),
        Socket\listen("[::]:{$port}"),
    ];

    $logger = LoggerFactory::create('console');

    $filesystemLoader = new FilesystemLoader(__DIR__.'/views/%name%');
    $templating = new PhpEngine(new TemplateNameParser(), $filesystemLoader);

//    $linkRepository = RedisLinkRepository::fromUri(getenv('REDIS_URI'));
    $linkRepository = InMemoryLinkRepository::init();

    $linkHandler = new LinkController(
        new LinkRenderer($templating),
        $linkRepository,
        $logger
    );

    $router = new Router();
    $router->addRoute('GET', '/', new CallableRequestHandler($linkHandler));
    $router->addRoute('POST', '/', new CallableRequestHandler($linkHandler));

    $server = new Server($sockets, $router, $logger);

    yield $server->start();
});
