<?php

namespace App\ViewModels\Docs\Old\References;

use Illuminate\Support\Str;

abstract class ClassLikeReference
{
    public function __construct(
        public string $name,
        public string $namespace,
    )
    {
    }

    public function getFullName(): string
    {
        return $this->namespace . '\\' . $this->name;
    }

    protected function getNamespaceForUrl(): string
    {
        return Str::replace('\\', '.', $this->namespace);
    }

    abstract public function getUrl(string $baseUrl = ''): string;

}
