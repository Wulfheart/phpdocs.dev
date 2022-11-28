<?php

namespace App\ViewModels\Navigation;


use ApiGen\Info\ClassLikeInfo;
use ApiGen\Info\FunctionInfo;

class ContentInfo
{
    public static function fromContentInfo(ClassLikeInfo|FunctionInfo $classLikeInfo): ContentInfo {
        return new self($classLikeInfo->name->short);
    }

    /**
     * @param ClassLikeInfo[]|FunctionInfo[] $infos
     * @return ContentInfo[]
     */
    public static function fromContentInfos(array $infos): array {
        return array_values(array_map(fn($info) => self::fromContentInfo($info), $infos));
    }

    public function __construct(
        public string $name,
        public bool   $isActive = false,
    ) {
    }

}
