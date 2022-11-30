<?php

namespace App\ViewModels\Docs;

use ApiGen\Info\ClassLikeReferenceInfo;
use ApiGen\Info\TraitInfo;

class ClassInfo
{
    public \ApiGen\Info\ClassInfo $original;

    /** @var array<\ApiGen\Info\ClassInfo> $extends */
    public array $extends;
    /** @var array<TraitInfo> $uses */
    public array $uses;
    /** @var array<ClassLikeReferenceInfo> $implements */
    public array $implements;

}
