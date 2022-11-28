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

        // This is just the first children of the primary namespace for now
        $rootNamespace = NamespaceInfo::fromNamespaceIndex($rootNamespaceIndex);

        return new self($rootNamespace);
    }

    /**
     * @param string[] $namespaces
     */
    public function activate(array $namespaces): void
    {
        $this->rootNamespace->activate($namespaces);

    }

}
