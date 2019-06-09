<?php
declare(strict_types=1);

namespace App\RequestHandler;

use Amp\Http\Server\FormParser;
use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use Amp\Http\Status;
use function Amp\Promise\wrap;
use App\Links\LinkRepositoryInterface;
use Psr\Log\LoggerInterface;

final class LinkController
{
    /** @var LinkRenderer */
    private $linkViewer;

    /** @var LinkRepositoryInterface */
    private $linkRepository;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        LinkRenderer $linkViewer,
        LinkRepositoryInterface $linkRepository,
        LoggerInterface $logger
    ) {
        $this->linkViewer = $linkViewer;
        $this->linkRepository = $linkRepository;
        $this->logger = $logger;
    }

    public function __invoke(Request $request)
    {
        $this->logger->info('Got a request');
        if ($request->getMethod() === 'POST') {
            $this->logger->info('Method is post! Parsing form');
            $form = yield FormParser\parseForm($request);
            $url = $form->getValue('url');
            $type = $form->getValue('type');
            $this->logger->info(sprintf('url %s type %s', $url, $type));
            if ($url) {
                if ('delete' === $type) {
                    $actionPromise = $this->linkRepository->removeLink($url);
                } else {
                    $actionPromise = $this->linkRepository->addLink($url);
                }

                return wrap($actionPromise, function ($e, $v) {
                    return $this->redirect();
                });
            }
        }

        return wrap(
            $this->linkViewer->view($this->linkRepository),
            function ($e, $v) {
                return new Response(Status::OK, [
                    "content-type" => "text/html; charset=utf-8"
                ], $v);
            }
        );
    }

    private function redirect(): Response
    {
        return new Response(Status::FOUND, ['Location' => '/']);
    }
}
