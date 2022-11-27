<?php

namespace App\ViewModels\Navigation;

use ApiGen\Index\NamespaceIndex;

class NamespaceInfo
{
    public static function fromNamespaceIndex(NamespaceIndex $index): NamespaceInfo
    {
        $children = [];
        foreach ($index->children as $child) {
            $children[] = self::fromNamespaceIndex($child);
        }
        return new self(
            $index->name->short,
            ContentInfo::fromContentInfos($index->class),
            ContentInfo::fromContentInfos($index->interface),
            ContentInfo::fromContentInfos($index->trait),
            ContentInfo::fromContentInfos($index->exception),
            ContentInfo::fromContentInfos($index->function),
            $children,
        );

    }

    public function __construct(
        public string $name,
        /** @var ContentInfo[] $classes */
        public array $classes,
        /** @var ContentInfo[] $interfaces */
        public array $interfaces,
        /** @var ContentInfo[] $traits */
        public array $traits,
        /** @var ContentInfo[] $exceptions */
        public array $exceptions,
        /** @var ContentInfo[] $functions */
        public array $functions,
        /** @var NamespaceInfo[] $children */
        public array $children,
    ) {
    }

}
