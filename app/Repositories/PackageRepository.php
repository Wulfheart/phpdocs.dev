<?php

namespace App\Repositories;

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
            $this->storeClass($class);
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

    protected function storeClass(ClassInfo $classInfo): void
    {
        $encoded = gzencode(serialize($classInfo));
        $this->filesystem->put(
            $this->basedir
            . DIRECTORY_SEPARATOR
            . sprintf(self::KEY_CLASS, str_replace('\\', '.', $classInfo->name->full))
            . '.serialized',
            $encoded
        );
    }

    public function getClass(string $namespace, string $className): ClassInfo
    {
        $encoded = $this->filesystem->get(
            $this->basedir
            . DIRECTORY_SEPARATOR
            . sprintf(self::KEY_CLASS, $namespace . '.' . $className)
            . '.serialized'
        );
        return unserialize(gzdecode($encoded));
    }

}
