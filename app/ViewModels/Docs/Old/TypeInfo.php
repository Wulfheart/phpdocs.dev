<?php

namespace App\ViewModels\Docs\Old;

use App\ViewModels\Docs\Old\References\ClassReference;

class TypeInfo
{
    public function __construct(
        /**
         * @var (string|ClassReference)[] $data
         */
        public array $data,
    ){
    }

}
