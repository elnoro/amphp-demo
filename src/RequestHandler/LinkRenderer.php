<?php
declare(strict_types=1);

namespace App\RequestHandler;

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

    public function view(LinkRepositoryInterface $linkCollection)
    {
        return wrap($linkCollection->getLinks(), function ($e, $v) {
            return $this->templating->render('links.php', ['links' => $v ?? []]);
        });
    }
}
