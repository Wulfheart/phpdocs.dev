<?php

namespace App\ViewModels\Navigation;

use ApiGen\Index\NamespaceIndex;

class NamespaceInfo
{
    public static function fromNamespaceIndex(NamespaceIndex $index, int $depth = 0): NamespaceInfo
    {
        $children = [];
        foreach ($index->children as $child) {
            $children[] = self::fromNamespaceIndex($child, $depth + 1);
        }
        return new self(
            $index->name->short,
            ContentInfo::fromContentInfos($index->class),
            ContentInfo::fromContentInfos($index->interface),
            ContentInfo::fromContentInfos($index->enum),
            ContentInfo::fromContentInfos($index->trait),
            ContentInfo::fromContentInfos($index->exception),
            FunctionInfo::fromContentInfos($index->function),
            $children,
            $depth,
        );

    }

    public function __construct(
        public string $name,
        /** @var ContentInfo[] $classes */
        public array $classes,
        /** @var ContentInfo[] $interfaces */
        public array $interfaces,
        /** @var ContentInfo[] $enums */
        public array $enums,
        /** @var ContentInfo[] $traits */
        public array $traits,
        /** @var ContentInfo[] $exceptions */
        public array $exceptions,
        /** @var ContentInfo[] $functions */
        public array $functions,
        /** @var NamespaceInfo[] $children */
        public array $children,
        public int $depth,
        public bool $showChildren = false,
        public bool $isActive = false,
        public bool $isLast = false,
    ) {
    }

    /**
     * @param string[] $namespaces
     */
    public function activate(array $namespaces, FunctionInfo|ContentInfo $info = null): ?NamespaceInfo {
        $namespace = array_shift($namespaces);
        if ($namespace === $this->name) {
            $this->isActive = true;
            $this->showChildren = true;
            if(count($namespaces) === 0) {
                $this->isLast = true;
                if($info !== null) {
                   $this->activateContentInfo($info);
                }
                return $this;
            }
            foreach ($this->children as $child) {
                $result = $child->activate($namespaces, $info);
                if($result !== null) {
                    return $result;
                }
            }
        }

        return null;
    }

    protected function activateContentInfo(FunctionInfo|ContentInfo $info): void {
        if($info instanceof FunctionInfo) {
            foreach ($this->functions as $function) {
                if($function->name === $info->name) {
                    $function->isActive = true;
                    return;
                }
            }
        } else {
            foreach ($this->classes as $class) {
                if($class->name === $info->name) {
                    $class->isActive = true;
                    return;
                }
            }
            foreach ($this->interfaces as $interface) {
                if($interface->name === $info->name) {
                    $interface->isActive = true;
                    return;
                }
            }
            foreach ($this->traits as $trait) {
                if($trait->name === $info->name) {
                    $trait->isActive = true;
                    return;
                }
            }
            foreach ($this->exceptions as $exception) {
                if($exception->name === $info->name) {
                    $exception->isActive = true;
                    return;
                }
            }
        }
    }
}



