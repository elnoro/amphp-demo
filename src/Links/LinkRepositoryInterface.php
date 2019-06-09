<?php

namespace App\Links;

use Amp\Promise;

interface LinkRepositoryInterface
{
    public function addLink(string $link): Promise;

    public function getLinks(): Promise;

    public function removeLink(string $linkToRemove): Promise;
}
