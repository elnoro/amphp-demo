<?php
declare(strict_types=1);

namespace App\RequestHandler;

use function Amp\call;
use function Amp\Promise\wrap;
use App\Links\LinkRepositoryInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;

final class LinkRenderer
{
    /** @var EngineInterface */
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public static function create(string $views): self
    {
        $filesystemLoader = new FilesystemLoader($views.'/%name%');
        $templating = new PhpEngine(new TemplateNameParser(), $filesystemLoader);

        return new self($templating);
    }

    /**
     * @param LinkRepositoryInterface $linkCollection
     * @return \Amp\Promise
     *
     * `call` wraps turns a coroutine method call into a promise, so that other parts of the project could consume it
     */
    public function view(LinkRepositoryInterface $linkCollection)
    {
        return call(function ($linkCollection) {
            return $this->render($linkCollection);
        }, $linkCollection);
    }

    private function render(LinkRepositoryInterface $linkCollection)
    {
        // using a normal try-catch block for asynchronous block of code
        // alternatively, the result can be treated as Promise, i. e. using `wrap` function
        // see example in LinkController::__invoke()
        try {
            $links = yield $linkCollection->getLinks();
        } catch (\Exception $e) {
            $links = [];
        }

        return $this->templating->render('links.php', ['links' => $links]);
    }
}
