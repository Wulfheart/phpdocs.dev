<?php

namespace App\Repositories;

use ApiGen\Index\Index;
use ApiGen\Info\ClassInfo;
use App\Actions\ApiGen\AnalyzeProjectAction;
use App\Actions\ApiGen\RetrieveIndexAction;
use App\Models\PackageVersion;
use App\ViewModels\Navigation;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class PackageRepository
{
    protected PackageVersion $packageVersion;
    protected Filesystem $filesystem;
    protected string $basedir;
    const KEY_NAVIGATION = "navigation.serialized";
    const KEY_CLASS = "classes/%s.serialized";

    public function __construct(
        protected AnalyzeProjectAction $analyzeProjectAction,
        protected RetrieveIndexAction  $retrieveIndexAction,
    )
    {
        $this->filesystem = Storage::disk('cached');
    }

    public function set(PackageVersion $packageVersion): self
    {
        $this->packageVersion = $packageVersion;
        $this->basedir = $this->packageVersion->id;
        return $this;
    }

    public function cache(): void
    {
        $analyzeResult = $this->analyzeProjectAction->execute($this->packageVersion);
        $index = $this->retrieveIndexAction->index($analyzeResult);

        $nav = Navigation::fromIndex($index);
        $this->storeNavigation($nav);

        foreach ($index->class as $class)
        {
            $this->storeClass($index, $class);
        }

        $this->packageVersion->cached_at = now();
        $this->packageVersion->save();

    }

    protected function storeNavigation(Navigation $navigation): void
    {
        $encoded = gzencode(serialize($navigation));
        $this->filesystem->put($this->basedir . DIRECTORY_SEPARATOR . self::KEY_NAVIGATION, $encoded);
    }

    public function getNavigation(): Navigation
    {
        $encoded = $this->filesystem->get($this->basedir . DIRECTORY_SEPARATOR . self::KEY_NAVIGATION);
        return unserialize(gzdecode($encoded));
    }

    protected function storeClass(Index $index, ClassInfo $classInfo): void
    {
        $ci = new \App\ViewModels\Docs\ClassInfo();
        $ci->original = $classInfo;
        $extends = $classInfo->extends;
        if(count($classInfo->implements) > 0){
            $ci->implements[] = $classInfo->implements;
        }
        $classInfo->implements[] = $classInfo->implements;
        while($extends != null){
            $class = $index->class[$extends->fullLower];
            $ci->extends[] = $class;
            if(count($class->implements) > 0){
                $ci->implements[] = $class->implements;
            }
            $extends = $class->extends;
        }





        $encoded = gzencode(serialize($ci));
        $this->filesystem->put(
            $this->basedir
            . DIRECTORY_SEPARATOR
            . sprintf(self::KEY_CLASS, str_replace('\\', '.', $classInfo->name->full)),
            $encoded
        );
    }

    public function getClass(string $namespace, string $className): \App\ViewModels\Docs\ClassInfo
    {
        $encoded = $this->filesystem->get(
            $this->basedir
            . DIRECTORY_SEPARATOR
            . sprintf(self::KEY_CLASS, $namespace . '.' . $className)
        );
        return unserialize(gzdecode($encoded));
    }

}
