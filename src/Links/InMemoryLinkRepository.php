<?php
declare(strict_types=1);

namespace App\Links;

use Amp\LazyPromise;
use Amp\Promise;
use Amp\Success;
use App\RequestHandler\LinkRenderer;

final class InMemoryLinkRepository implements LinkRepositoryInterface
{
    /** @var string[] */
    private $links;

    public static function init(): self
    {
        return new self([]);
    }

    public function __construct(array $links)
    {
        $this->links = $links;
    }

    public function addLink(string $link): Promise
    {
        $this->links[] = $link;

        return new Success();
    }

    public function getLinks(): Promise
    {
        return new Success($this->links);
    }

    public function removeLink(string $linkToRemove): Promise
    {
        foreach ($this->links as $key => $link) {
            if ($linkToRemove === $link) {
                unset($this->links[$key]);
            }
        }

        return new Success();
    }
}
