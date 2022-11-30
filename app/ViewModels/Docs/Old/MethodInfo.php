<?php

namespace App\ViewModels\Docs\Old;

class MethodInfo
{
    public function __construct(
        public string $name,
        public string $description,
        public TypeInfo $returnType,
        /**
         * @var array<ParameterInfo> $parameters
         */
        public array $parameters,
    )
    {
    }
}
