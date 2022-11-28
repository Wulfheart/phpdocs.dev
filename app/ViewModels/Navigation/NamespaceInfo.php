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
        public bool $showChildren = false,
        public bool $isActive = false,
    ) {
    }

    /**
     * @param string[] $namespaces
     */
    public function activate(array $namespaces): void {
        if (count($namespaces) === 0) {
            return;
        }
        $namespace = array_shift($namespaces);
        if ($namespace === $this->name) {
            $this->isActive = true;
            $this->showChildren = true;
            foreach ($this->children as $child) {
                $child->activate($namespaces);
            }
        }
    }

}
