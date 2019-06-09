<?php
declare(strict_types=1);

namespace App\Links;

use Amp\Promise;
use Amp\Redis\Client;

final class RedisLinkRepository implements LinkRepositoryInterface
{
    private const LINKS = 'links';
    /** @var Client */
    private $client;

    public static function fromUri(string $redisUri): self
    {
        $client = new Client($redisUri);

        return new RedisLinkRepository($client);
    }

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function addLink(string $link): Promise
    {
        return $this->client->sAdd(self::LINKS, $link);
    }

    public function getLinks(): Promise
    {
        return $this->client->sMembers(self::LINKS);
    }

    public function removeLink(string $linkToRemove): Promise
    {
        return $this->client->sRem(self::LINKS, $linkToRemove);
    }
}
