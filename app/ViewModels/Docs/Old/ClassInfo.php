<?php

namespace App\ViewModels\Docs\Old;

use App\ViewModels\Docs\Old\References\ClassLikeReference;

class ClassInfo
{
    public bool $abstract = false;
    public bool $final = false;
    public bool $readOnly = false;
    public string $name;
    public string $namespace;
    public string $description;
    /** @var array<ClassLikeReference> $properties */
    public array $implements;
    public ?ClassLikeReference $extends;
    /** @var array<ClassLikeReference> $properties */
    public array $uses;
    /** @var array<PropertyInfo> $properties */
    public array $properties;
    /** @var array<MethodInfo> $methods */
    public array $methods;

}
