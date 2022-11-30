<?php

namespace App\ViewModels\Docs\Old\References;

class ClassReference extends ClassLikeReference
{
    public function getUrl(string $baseUrl = ''): string
    {
        return $baseUrl . '/' . $this->getNamespaceForUrl() . '/class/' . $this->name;
    }

}
