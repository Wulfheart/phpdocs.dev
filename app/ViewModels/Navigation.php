<?php

namespace App\ViewModels;

use ApiGen\Index\Index;
use ApiGen\Index\NamespaceIndex;
use App\ViewModels\Navigation\NamespaceInfo;

class Navigation
{

    public function __construct(
        public NamespaceInfo $rootNamespace,
    ) {
    }

    public static function fromIndex(Index $index): self
    {
        $rootNamespaceIndex = collect($index->namespace)->first(fn(NamespaceIndex $ni) => $ni->primary);
        $rootNamespace = NamespaceInfo::fromNamespaceIndex($rootNamespaceIndex);
        return new self($rootNamespace);
    }

}
